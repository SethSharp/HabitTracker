<?php

namespace Tests\Http\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
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
    public function user_can_update_information()
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

    /** @test */
    public function user_can_delete_their_account(): void
    {
        $this->actingAs($this->user)
            ->delete('/profile', [
                'password' => 'password',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($this->user->fresh());
    }

    /** @test */
    public function correct_password_must_be_provided_to_delete_account(): void
    {
        $this->actingAs($this->user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors('password')
            ->assertRedirect('/profile');

        $this->assertNotNull($this->user->fresh());
    }
}
