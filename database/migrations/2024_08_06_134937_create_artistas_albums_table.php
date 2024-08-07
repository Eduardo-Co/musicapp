<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistasAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artistas_albums', function (Blueprint $table) {
            $table->unsignedBigInteger('artista_id');
            $table->unsignedBigInteger('album_id');
            
            $table->primary(['artista_id', 'album_id']);

            $table->foreign('artista_id')
                  ->references('id')
                  ->on('artistas')
                  ->onDelete('cascade');

            $table->foreign('album_id')
                  ->references('id')
                  ->on('albums')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artistas_albums');
    }
}
