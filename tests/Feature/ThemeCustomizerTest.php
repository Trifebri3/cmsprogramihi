<?php

namespace Tests\Feature;

use App\Models\Theme;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeCustomizerTest extends TestCase
{
    use RefreshDatabase;

    protected $program;
    protected $programAdmin;
    protected $theme;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Spatie Roles
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'program-admin']);

        $this->theme = Theme::create(['name' => 'Theme Customizer Test']);
        $this->program = Program::create([
            'theme_id' => $this->theme->id,
            'name' => 'Customizer Test',
            'slug' => 'customizer-test',
            'subdomain' => 'customizer-test',
            'status' => 'active'
        ]);

        $this->programAdmin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'program_id' => $this->program->id
        ]);
        $this->programAdmin->assignRole($adminRole);
    }

    /**
     * Test that contact details and custom theme settings can be updated successfully.
     */
    public function test_program_admin_can_update_contact_and_theme(): void
    {
        $this->actingAs($this->programAdmin);

        $response = $this->patch('/cms/theme', [
            'primary_color' => '#112233',
            'secondary_color' => '#445566',
            'accent_color' => '#778899',
            'font_heading' => 'Roboto',
            'font_body' => 'Lato',
            'navbar_layout' => 'left',
            'footer_layout' => 'simple',
            'hero_layout' => 'split',
            'template_layout' => 'ngo',
            'hero_title' => 'Custom Hero Title Text',
            'hero_subtitle' => 'Custom Hero Subtitle Text description block',
            'hero_btn_text' => 'Custom Action Label',
            'contact_keys' => ['Email Resmi', 'Nomor Telepon', 'Alamat Kantor', 'WhatsApp Link'],
            'contact_values' => ['contact@testprogram.com', '021-987654', 'Jalan Merdeka No. 10', 'https://wa.me/6289999999'],
        ]);

        $response->assertRedirect('/cms/theme');
        $response->assertSessionHas('status');

        // Check program contact details are saved and cast correctly
        $program = Program::find($this->program->id);
        $this->assertIsArray($program->contact);
        $this->assertEquals('contact@testprogram.com', $program->getContact('Email Resmi'));
        $this->assertEquals('021-987654', $program->getContact('Nomor Telepon'));
        $this->assertEquals('Jalan Merdeka No. 10', $program->getContact('Alamat Kantor'));
        
        // Assert that the public theme page renders this new color
        $publicResponse = $this->withHeaders(['Host' => 'customizer-test.localhost'])
            ->get('/customizer-test');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('--color-primary: #112233');
        $publicResponse->assertSee('WhatsApp Link');
        $publicResponse->assertSee('https://wa.me/6289999999');
        $publicResponse->assertSee('Custom Hero Title Text');
        $publicResponse->assertSee('Custom Hero Subtitle Text description block');
    }
}
