<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Program;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $program;
    protected $programAdmin;
    protected $theme;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $progAdminRole = Role::create(['name' => 'program-admin']);

        // Create theme & program
        $this->theme = Theme::create(['name' => 'Demo Theme']);
        $this->program = Program::create([
            'theme_id' => $this->theme->id,
            'name' => 'Program Demo',
            'slug' => 'program-demo',
            'subdomain' => 'program-demo',
            'status' => 'active'
        ]);

        // Create Program Admin User
        $this->programAdmin = User::create([
            'name' => 'Demo Program Admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('password'),
            'program_id' => $this->program->id
        ]);
        $this->programAdmin->assignRole($progAdminRole);
    }

    /**
     * Test that Program Admin can CRUD Categories.
     */
    public function test_program_admin_can_crud_categories(): void
    {
        $this->actingAs($this->programAdmin);

        // 1. Create Category
        $response = $this->post('/cms/categories', [
            'name' => 'Kesehatan Masyarakat',
            'slug' => 'kesehatan-masyarakat'
        ]);

        $response->assertRedirect('/cms/categories');
        $this->assertDatabaseHas('categories', [
            'program_id' => $this->program->id,
            'name' => 'Kesehatan Masyarakat',
            'slug' => 'kesehatan-masyarakat'
        ]);

        $category = Category::where('slug', 'kesehatan-masyarakat')->first();

        // 2. Edit Category View
        $response = $this->get('/cms/categories/' . $category->id . '/edit');
        $response->assertStatus(200);

        // 3. Update Category
        $response = $this->patch('/cms/categories/' . $category->id, [
            'name' => 'Kesehatan Publik',
            'slug' => 'kesehatan-publik'
        ]);

        $response->assertRedirect('/cms/categories');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Kesehatan Publik',
            'slug' => 'kesehatan-publik'
        ]);

        // 4. Delete Category
        $response = $this->delete('/cms/categories/' . $category->id);
        $response->assertRedirect('/cms/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * Test that creating articles syncs multiple categories correctly.
     */
    public function test_creating_content_with_categories(): void
    {
        $this->actingAs($this->programAdmin);

        // Create categories
        $cat1 = Category::create([
            'program_id' => $this->program->id,
            'name' => 'Pendidikan',
            'slug' => 'pendidikan'
        ]);

        $cat2 = Category::create([
            'program_id' => $this->program->id,
            'name' => 'Sosial',
            'slug' => 'sosial'
        ]);

        // Create post tagged with categories
        $response = $this->post('/cms/contents', [
            'title' => 'Aksi Belajar Bersama',
            'slug' => 'aksi-belajar-bersama',
            'type' => 'post',
            'status' => 'published',
            'content_text' => 'Mengajarkan membaca anak-anak.',
            'categories' => [$cat1->id, $cat2->id]
        ]);

        $response->assertRedirect('/cms/contents');

        $post = Content::where('slug', 'aksi-belajar-bersama')->first();
        $this->assertNotNull($post);
        $this->assertCount(2, $post->categories);
        $this->assertTrue($post->categories->contains($cat1->id));
        $this->assertTrue($post->categories->contains($cat2->id));
    }

    /**
     * Test that public category filtering shows correct posts.
     */
    public function test_public_category_filtering(): void
    {
        // Create category
        $category = Category::create([
            'program_id' => $this->program->id,
            'name' => 'Kategori Publik',
            'slug' => 'kategori-publik'
        ]);

        // Create post tagged with category
        $post = Content::create([
            'program_id' => $this->program->id,
            'author_id' => $this->programAdmin->id,
            'type' => 'post',
            'title' => 'Post Tagged',
            'slug' => 'post-tagged',
            'status' => 'published',
            'published_at' => now(),
            'content_blocks' => [['type' => 'text', 'body' => 'Body content']]
        ]);
        $post->categories()->sync([$category->id]);

        // Set subdomain context
        $response = $this->withHeaders(['Host' => 'program-demo.localhost'])
            ->get('/program-demo/category/kategori-publik');

        $response->assertStatus(200);
        $response->assertSee('Post Tagged');
    }
}
