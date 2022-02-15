<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(
            [
                'name' => 'webdmin',
                'email' => 'webadmin@gmail.com',
                'password' => Hash::make('Santu@123'),
                'country'=>'103',
                'activation_token'=>'',
                'active'=>1
            ]);
        $user->assignRole('admin');
    }
}
