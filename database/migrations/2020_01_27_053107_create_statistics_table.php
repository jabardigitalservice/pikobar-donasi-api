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
            $table->uuid('id')->unique()->primary();
            $table->bigInteger('personal_investor')->default(0)->comment('Jumlah donatur perorangan');
            $table->bigInteger('company_investor')->default(0)->comment('Jumlah donatur percompany');
            $table->bigInteger('total_goods')->default(0)->comment('Jumlah barang yang terkumpul');
            $table->decimal('total_cash', 15, 2)->default(0)->comment('Jumlah dana tunai yang terkumpul');
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
