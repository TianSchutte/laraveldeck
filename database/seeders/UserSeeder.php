<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1000)->create();

        //admin
        $user = User::create([
            'name' => 'tian',
            'surname' => 'schutte',
            'email' => 'tianschutte1@gmail.com',
            'password' => bcrypt('aaaaaaaa')
        ]);

        $user->assignRole('Admin');
    }
}
