<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->string('id', 50)->unique()->primary();
            $table->bigInteger('personal_investor')->default(0)->comment('Jumlah donatur perorangan');
            $table->bigInteger('company_investor')->default(0)->comment('Jumlah donatur percompany');
            $table->bigInteger('total_goods')->default(0)->comment('Jumlah barang yang terkumpul');
            $table->decimal('total_cash', 15, 2)->default(0)->comment('Jumlah dana tunai yang terkumpul');
            $table->dateTime('date_input');
            $table->string('last_key', 50)->nullable();
            $table->tinyInteger('is_last')->default(0);
            $table->timestamps();
            $table->index('personal_investor');
            $table->index('company_investor');
            $table->index('total_goods');
            $table->index('total_cash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}
