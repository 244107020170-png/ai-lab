<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Volunteer;

class VolunteerFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function volunteer_form_can_be_submitted()
    {
        $response = $this->post('/volunteer', [
            'fullname' => 'Test User',
            'nickname' => 'Tester',
            'dept' => 'Informatics',
            'semester' => '5',
            'email' => 'test@example.com',
            'phone' => '08123456789',
            'areas' => ['AI', 'Web'],
            'skills' => 'Python, JS',
            'motivation' => 'I want to join.',
            'availability' => 'Weekdays',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('volunteers', [
            'fullname' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
