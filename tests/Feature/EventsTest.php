<?php

namespace Tests\Feature;

use App\Models\Events;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The index should at least return 200
     */
    public function test_index_response_ok(): void
    {
        $response = $this->get('/api/events');
        $response->assertStatus(200);
    }

    /**
     * The index should at least return 200
     */
    public function test_reservations_response_ok(): void
    {
        Events::create([
            "title" => "new title",
            "description" => "new description",
            "location" => "calle 10",
            "date" => "2024-12-31",
            "capacity" => 100,
        ]);

        $response = $this->get("/api/events/1/reservations");
        $response->assertStatus(200);
    }

    static function createAdminUser(): string
    {
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com'
        ]);
        return $user->createToken('tmp', ['is-admin'])->plainTextToken;
    }
}
