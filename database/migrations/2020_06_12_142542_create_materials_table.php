<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->string('id', 50)->unique()->primary();
            $table->bigInteger('id_pos');
            $table->string('matg_id');
            $table->bigInteger('sisa')->nullable()->default(0);
            $table->bigInteger('masuk')->nullable()->default(0);
            $table->bigInteger('distribusi')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(0);
            $table->tinyInteger('status_medis')->nullable()->default(0);
            $table->tinyInteger('is_show')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('sisa');
            $table->index('masuk');
            $table->index('distribusi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
