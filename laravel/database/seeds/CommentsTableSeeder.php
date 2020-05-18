<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = new App\Comment;
        $comments-> user_id = 1;
        $comments-> shoppinglist_id = 1;
        $comments-> content = "Das hier ist ein Testkommentar";
        $comments->save();

        $comments2 = new App\Comment;
        $comments2-> user_id = 1;
        $comments2-> shoppinglist_id = 1;
        $comments2-> content = "Das hier ist ein Testkommentar Nr.2";
        $comments2->save();
    }
}
