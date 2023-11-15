<?php

namespace Database\Seeders;

use App\Domain\Iam\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(5)->create();
    }
}
