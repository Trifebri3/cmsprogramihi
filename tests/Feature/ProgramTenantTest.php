<?php

namespace Tests\Feature;

use App\Models\Theme;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramTenantTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an active program is loaded and matches its theme parameters.
     */
    public function test_program_profile_loads_successfully_with_theme(): void
    {
        $theme = Theme::create([
            'name' => 'Test Green Theme',
            'primary_color' => '#123456',
            'secondary_color' => '#FFFFFF',
            'accent_color' => '#FFB300',
            'font_heading' => 'Poppins',
            'font_body' => 'Inter',
        ]);

        $program = Program::create([
            'theme_id' => $theme->id,
            'name' => 'Generaz Berbakti Test',
            'slug' => 'generaz-test',
            'subdomain' => 'generaztest',
            'status' => 'active',
        ]);

        // Access the public subsite path
        $response = $this->get('/p/generaz-test');

        $response->assertStatus(200);
        $response->assertSee('Generaz Berbakti Test');
        
        // Assert that the primary styling color variable is dynamically injected in the HTML head
        $response->assertSee('--color-primary: #123456');
    }

    /**
     * Test that an invalid program slug throws a 404 page.
     */
    public function test_invalid_program_slug_returns_404(): void
    {
        $response = $this->get('/p/non-existent-program');

        $response->assertStatus(404);
    }
}
