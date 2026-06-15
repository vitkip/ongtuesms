<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use Livewire\Livewire;
use App\Livewire\SettingsManagement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsLogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_requires_auth()
    {
        $response = $this->get('/settings');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_save_settings_and_upload_logo()
    {
        Storage::fake('public');

        // Create admin user using factory
        // Note: the schema requires username and password (which factory defaults)
        $admin = User::create([
            'username' => 'testadmin',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'full_name' => 'Test Admin',
            'email' => 'admin@test.com',
            'is_active' => true,
        ]);
        
        $this->actingAs($admin);

        // Verify we can access settings page
        $response = $this->get('/settings');
        $response->assertStatus(200);

        // Verify Livewire component logic
        $file = UploadedFile::fake()->image('custom_logo.png', 100, 100);

        Livewire::test(SettingsManagement::class)
            ->set('school_name', 'Test Sangha School')
            ->set('logo', $file)
            ->call('saveSettings')
            ->assertHasNoErrors();

        // Assert setting was updated in database
        $logoSetting = Setting::getVal('school_logo');
        $this->assertNotEmpty($logoSetting);
        
        // Assert file exists in storage
        Storage::disk('public')->assertExists($logoSetting);
        
        // Assert school name was saved
        $this->assertEquals('Test Sangha School', Setting::getVal('school_name'));
    }
}
