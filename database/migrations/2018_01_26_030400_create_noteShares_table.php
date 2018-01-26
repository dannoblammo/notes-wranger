<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_shares', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('note_id')
                  ->references('id')
                  ->on('notes')
                  ->onDelete('cascade');
            $table->string('email');
            $table->tinyInteger('is_read_only')->default(1);

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
        Schema::dropIfExists('note_shares');
    }
}
