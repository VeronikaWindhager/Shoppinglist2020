<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table -> foreign ( 'user_id')
                -> references ( 'id' )-> on ( 'users')
                -> onDelete ( 'cascade' );
            $table->unsignedBigInteger('shoppinglist_id');
            $table -> foreign ( 'shoppinglist_id')
                -> references ( 'id' )-> on ( 'shoppinglists')
                -> onDelete ( 'cascade' );
            $table->string('content');
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
        Schema::dropIfExists('comments');
    }
}
