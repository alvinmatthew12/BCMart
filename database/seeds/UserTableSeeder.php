<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Admin",
            "email" => "admin@mail.com",
            "password" => bcrypt("admin"),
            "role_id" => 1
        ]);
        User::create([
            "name" => "Alvin Matthew",
            "email" => "merchant_alvin@mail.com",
            "password" => bcrypt("alvin"),
            "role_id" => 2
        ]);
        User::create([
            "name" => "Alvin Matthew",
            "email" => "alvin@mail.com",
            "password" => bcrypt("alvin"),
            "role_id" => 3
        ]);
        User::create([
            "name" => "Cindy Adonia",
            "email" => "cindy@mail.com",
            "password" => bcrypt("2<;2{TAe/p}tGM3g"),
            "role_id" => 3
        ]);
    }
}
