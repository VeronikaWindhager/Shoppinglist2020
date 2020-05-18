<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items1 = new App\Item;
        $items1-> shoppinglist_id = 1;
        $items1-> name = "Testitem";
        $items1-> amount = "3 Stück";
        $items1-> max_price = 29.00;
        $items1-> save();

        $items2 = new App\Item;
        $items2-> shoppinglist_id = 1;
        $items2-> name = "Testitem2";
        $items2-> amount = "1 Stück";
        $items2-> max_price = 59.00;
        $items2-> save();
    }
}
