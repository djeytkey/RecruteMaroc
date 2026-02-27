<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_candidates_can_register_with_email_and_password_only(): void
    {
        $response = $this->post('/register', [
            'type' => 'candidat',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('candidat.profile.edit'));
    }

    public function test_new_recruiters_can_register_with_full_details(): void
    {
        $response = $this->post('/register', [
            'type' => 'recruteur',
            'name' => 'Test Recruiter',
            'email' => 'recruiter@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => 'Test Company',
            'company_email' => 'contact@company.com',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
