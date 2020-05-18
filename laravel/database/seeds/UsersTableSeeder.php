<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new App\User;
        $user-> first_name = "Max";
        $user-> last_name = "Musterman";
        $user-> email = "test@gmail.com";
        $user-> password = bcrypt('secret');
        $user-> street = "Luftstraße";
        $user-> number = "2";
        $user-> zip = 4210;
        $user-> city = "Linz";
        $user->role = false;
        $user->save();

        $user2 = new App\User;
        $user2-> first_name = "Luisa";
        $user2-> last_name = "Musterfrau";
        $user2-> email = "test2@gmail.com";
        $user2-> password = bcrypt('topsecret');
        $user2-> street = "Liebesweg";
        $user2-> number = "45";
        $user2-> zip = 3210;
        $user2-> city = "Wien";
        $user2->role = true;
        $user2->save();
    }
}
