<?php

namespace Tests\Http\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function must_be_authenticated()
    {
        $this->patch(route("profile.update"))
            ->assertRedirect('/login');

        $this->assertDatabaseCount('users', 1);
    }

    /** @test */
    public function fields_are_required(): void
    {
        $userData = [
            'name' => '',
            'email' => '',
        ];

        $this->actingAs($this->user)
            ->patch(route("profile.update", $userData))
            ->assertSessionHasErrors([
                'name',
                'email',
            ]);
    }

    /** @test */
    public function name_must_be_more_than_5_characters()
    {
        $userData = [
            'name' => '1234',
            'email' => 'newtesting@email.com',
        ];

        $this->actingAs($this->user)
            ->patch(route("profile.update", $userData))
            ->assertSessionHasErrors([
                'name',
            ]);
    }

    /** @test */
    public function can_save_user_data()
    {
        $userData = [
            'name' => 'New Name',
            'email' => 'testing@testing.com',
        ];

        $this->actingAs($this->user)
            ->patch(route("profile.update", $userData))
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'name' => 'New Name',
            'email' => 'testing@testing.com',
        ]);
    }
}
