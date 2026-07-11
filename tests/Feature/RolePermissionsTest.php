<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\User;
use App\Models\Content;
use App\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected $program1;
    protected $program2;
    protected $programAdmin;
    protected $editorUser;
    protected $contributorUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        Role::create(['name' => 'super-admin']);
        $progAdminRole = Role::create(['name' => 'program-admin']);
        $editorRole = Role::create(['name' => 'editor']);
        $contribRole = Role::create(['name' => 'contributor']);

        // Create theme
        $theme = Theme::create(['name' => 'Test Theme']);

        // Create programs
        $this->program1 = Program::create([
            'theme_id' => $theme->id,
            'name' => 'Program Satu',
            'slug' => 'program-satu',
            'status' => 'active'
        ]);

        $this->program2 = Program::create([
            'theme_id' => $theme->id,
            'name' => 'Program Dua',
            'slug' => 'program-dua',
            'status' => 'active'
        ]);

        // Create users for program 1
        $this->programAdmin = User::create([
            'name' => 'Program Admin One',
            'email' => 'admin1@test.com',
            'password' => bcrypt('password'),
            'program_id' => $this->program1->id
        ]);
        $this->programAdmin->assignRole($progAdminRole);

        $this->editorUser = User::create([
            'name' => 'Editor One',
            'email' => 'editor1@test.com',
            'password' => bcrypt('password'),
            'program_id' => $this->program1->id
        ]);
        $this->editorUser->assignRole($editorRole);

        $this->contributorUser = User::create([
            'name' => 'Contributor One',
            'email' => 'contrib1@test.com',
            'password' => bcrypt('password'),
            'program_id' => $this->program1->id
        ]);
        $this->contributorUser->assignRole($contribRole);
    }

    /**
     * Test that Editor can create content but status is forced to draft if they try to publish.
     */
    public function test_editor_cannot_publish_content_directly(): void
    {
        $this->actingAs($this->editorUser);

        $response = $this->post('/cms/contents', [
            'title' => 'Artikel Editor',
            'slug' => 'artikel-editor',
            'type' => 'post',
            'status' => 'published', // Try to publish
            'content_text' => 'Konten teks editor.',
        ]);

        $response->assertRedirect('/cms/contents');
        
        // Assert content is created but status was overridden to 'draft'
        $content = Content::where('slug', 'artikel-editor')->first();
        $this->assertNotNull($content);
        $this->assertEquals('draft', $content->status);
    }

    /**
     * Test that Program Admin CAN publish content directly.
     */
    public function test_program_admin_can_publish_content_directly(): void
    {
        $this->actingAs($this->programAdmin);

        $response = $this->post('/cms/contents', [
            'title' => 'Artikel Admin',
            'slug' => 'artikel-admin',
            'type' => 'post',
            'status' => 'published',
            'content_text' => 'Konten teks admin.',
        ]);

        $response->assertRedirect('/cms/contents');
        
        // Assert content status remains 'published'
        $content = Content::where('slug', 'artikel-admin')->first();
        $this->assertNotNull($content);
        $this->assertEquals('published', $content->status);
    }

    /**
     * Test that Program Admin can CRUD users for their own program only.
     */
    public function test_program_admin_cannot_edit_users_from_other_programs(): void
    {
        // User from program 2
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
            'program_id' => $this->program2->id
        ]);

        $this->actingAs($this->programAdmin);

        // Try to access the edit screen of other program user
        $response = $this->get('/cms/users/' . $otherUser->id . '/edit');

        $response->assertStatus(403);
    }
}
