<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppinglistUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoppinglist_user', function (Blueprint $table) {
            $table->unsignedBigInteger('shoppinglist_id')->index();
            $table -> foreign ( 'shoppinglist_id')
                -> references ( 'id' )-> on ( 'shoppinglists')
                -> onDelete ( 'cascade' );

            $table->unsignedBigInteger('user_id')->index();
            $table -> foreign ( 'user_id')
                -> references ( 'id' )-> on ( 'users')
                -> onDelete ( 'cascade' );

            $table->primary(['shoppinglist_id', 'user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shoppinglist_user');
    }
}
