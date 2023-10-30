<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@qada.com',
            'password' => Hash::make('112233'),
            'name' => 'Qada Admin',
            'phone' => '+2010',
            'active' => 1,
            'email_verified_at' => '2023-06-30 08:13:55',
            'role_id' => 1,
        ]);
        User::create([
            'email' => 'bussiness@qada.com',
            'password' => Hash::make('112233'),
            'name' => 'Qada Bussiness',
            'phone' => '+2011',
            'active' => 1,
            'email_verified_at' => '2023-06-30 08:13:55',
            'role_id' => 2,
        ]);
        User::create([
            'email' => 'office@qada.com',
            'password' => Hash::make('112233'),
            'name' => 'Qada Office',
            'phone' => '+2012',
            'active' => 1,
            'email_verified_at' => '2023-06-30 08:13:55',
            'role_id' => 3,
        ]);
        User::create([
            'email' => 'person@qada.com',
            'password' => Hash::make('112233'),
            'name' => 'Qada Person',
            'phone' => '+2013',
            'active' => 1,
            'email_verified_at' => '2023-06-30 08:13:55',
            'role_id' => 4,
        ]);
    }
}
