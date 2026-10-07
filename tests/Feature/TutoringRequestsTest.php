<?php

namespace Tests\Feature;

use App\Models\Tutoring;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutoringRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_tutoring_request_can_be_saved(): void
    {
        $response = $this->postJson('/api/tutoring-requests', [
            'student_name' => 'Jane Doe',
            'station_number' => 'PC-18',
            'topic' => 'HTML/CSS',
            'issue_summary' => 'CSS styles are not applying.',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('student_name', 'Jane Doe')
            ->assertJsonPath('status', 'Pending');

        $this->assertDatabaseHas('tutorings', [
            'student_name' => 'Jane Doe',
            'station_number' => 'PC-18',
            'status' => 'Pending',
        ]);
    }

    public function test_a_tutoring_request_status_can_be_updated(): void
    {
        $tutoring = Tutoring::create([
            'student_name' => 'Jane Doe',
            'station_number' => 'PC-18',
            'topic' => 'HTML/CSS',
            'issue_summary' => 'CSS styles are not applying.',
            'status' => 'Pending',
        ]);

        $this->putJson("/api/tutoring-requests/{$tutoring->id}", [
            'status' => 'Resolved',
        ])
            ->assertOk()
            ->assertJsonPath('status', 'Resolved');

        $this->assertDatabaseHas('tutorings', [
            'id' => $tutoring->id,
            'status' => 'Resolved',
        ]);
    }
}
