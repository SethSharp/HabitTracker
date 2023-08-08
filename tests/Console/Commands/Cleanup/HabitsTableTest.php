<?php

namespace Tests\Console\Commands\Cleanup;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use Tests\Traits\RefreshDatabase;

class HabitsTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deletes_soft_deleted_habits()
    {
        $user = User::factory()->create();

        $habit1 = Habit::factory()->create([
            'user_id' => $user->id,
            'deleted_at' => "2023-08-06"
        ]);

        $habit2 = Habit::factory()->create([
            'user_id' => $user->id,
            'deleted_at' => "2023-08-07"
        ]);

        $habit3 = Habit::factory()->create([
            'user_id' => $user->id,
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-07"));

        $this->artisan('cleanup:deleted-habits-table')
            ->assertSuccessful();

        $this->assertDatabaseMissing('habits', [
            'id' => $habit1->id,
        ]);

        $this->assertDatabaseMissing('habits', [
            'id' => $habit2->id,
        ]);

        $this->assertDatabaseHas('habits', [
            'id' => $habit3->id,
            'deleted_at' => null
        ]);
    }
}
