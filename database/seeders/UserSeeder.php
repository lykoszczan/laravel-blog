<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->admin()->create(['email' => 'admin@test.pl']);
        User::factory()->editor()->create(['email' => 'editor@test.pl']);
        User::factory()->user()->create(['email' => 'user@test.pl']);
    }
}
