<?php

namespace Tests\Console\Commands;

use Tests\TestCase;
use Codinglabs\Roles\Role;
use App\Domain\Iam\Models\User;
use Tests\Traits\RefreshDatabase;

class FixTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_is_assigned_admin_role()
    {
        Role::create(['name' => User::ROLE_ADMIN]);
        
        $user = User::factory()->create([
            'email' => 'sesharp@outlook.com'
        ]);

        $this->artisan('fix:things')
            ->assertOk();

        $this->assertDatabaseHas('role_user', [
            'role_id' => Role::whereName(User::ROLE_ADMIN)->first()->id,
            'user_id' => $user->id
        ]);
    }
}
