<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('code', 150)->comment('Kode Unik Bank');
            $table->string('name', 150)->comment('Nama bank');
            $table->string('xendit_code', 255)->comment('Code dari xendit');
            $table->timestamps();
            $table->index('code');
            $table->index('name');
            $table->index('xendit_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
