<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // eloquent : query atau orm laravel
        // model = acuan ke table
        Role::insert([
            ['name' => 'Administrator'],
            ['name' => 'Cashier'],
            ['name' => 'Leader'],
        ]);
    }
}
