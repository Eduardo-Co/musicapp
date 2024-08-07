<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('genre', ['Rock', 'Pop', 'Hip Hop', 'R&B', 'Country', 'Jazz', 'Reggae', 'Electronic', 'Classical'])->nullable();
            $table->date('release_date')->nullable();
            $table->integer('duration');
            $table->enum('status', ['actived', 'inactived']);
            $table->string('file_url');

            $table->unsignedBigInteger('album_id')->nullable();
            $table->foreign('album_id')
                ->references('id')
                ->on('albums')
                ->onDelete('cascade');

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
        Schema::dropIfExists('musics');
    }
}
