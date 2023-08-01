<?php

namespace Tests\Http\Auth;

use App\Http\Events\RegisteredEvent;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Tests\Traits\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /** @test */
    public function new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example1.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function registered_event_triggered_on_registration()
    {
        Event::fake();

        $this->post('/register', [
            'name' => 'Test User 1',
            'email' => 'test@example2.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Event::assertDispatched(RegisteredEvent::class);
    }
}
