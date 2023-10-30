<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'role_name' => 'admin'
        ]);
        Role::create([
            'role_name' => 'bussiness'
        ]);
        Role::create([
            'role_name' => 'office'
        ]);
        Role::create([
            'role_name' => 'person'
        ]);
    }
}
