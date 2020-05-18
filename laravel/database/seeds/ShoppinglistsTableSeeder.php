<?php

use Illuminate\Database\Seeder;

class ShoppinglistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lists = new App\Shoppinglist;
        $lists-> title = "Wocheneinkauf";
        $lists-> due_date = new DateTime();
        $lists-> total_price = 23.45;
        $lists-> image = null;
        $lists-> save();

        $user = App\User::all()->first();
        $lists->user()->sync($user);
        $lists-> save();


        $lists2 = new App\Shoppinglist;
        $lists2-> title = "Tageseinkauf";
        $lists2-> due_date = new DateTime();
        $lists2-> total_price = 45.45;
        $lists2-> image = null;
        $lists2-> save();

        $user2 = App\User::all()->first();
        $lists2-> user()->sync($user2);
        $lists2-> save();
    }
}
