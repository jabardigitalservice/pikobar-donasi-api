<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateImagesTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->string('id', 255);
            $table->text('name');
            $table->text('extension');
            $table->text('path');
            $table->text('image_url');
            $table->text('data_type');
            $table->timestamps();
            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('images');
    }
}
