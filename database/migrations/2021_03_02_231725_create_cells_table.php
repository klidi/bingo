<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cells', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id');
            $table->index('card_id');
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');

            // a bit of denormalization here. it will help with filtering
            $table->unsignedBigInteger('game_id');
            $table->index('game_id');
            $table->foreign('game_id')->references('id')->on('games');

            $table->integer('value')->nullable(false);
            $table->index('value');
            $table->integer('row')->nullable(false);
            $table->integer('col')->nullable(false);
            $table->boolean('checked')->nullable(true);
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
        Schema::dropIfExists('cells');
        // here we need some maintenance to drop index data ad foreign key
    }
}
