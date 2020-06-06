<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateSembakoPackagesTable.
 */
class CreateSembakoPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sembako_packages', function (Blueprint $table) {
            $table->string('id', 255);
            $table->string('sku', 100);
            $table->string('package_name', 100);
            $table->text('package_description')->nullable();
            $table->tinyInteger('status')->default(1)->nullable()->comment('1=aktif 0 = nonaktif');
            $table->string('last_modified_by', 255)->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->primary(['id']);
            $table->timestamps();
            $table->softDeletes();

            $table->index('sku');
            $table->index('package_name');
            $table->index('status');
        });

        Schema::create('sembako_package_items', function (Blueprint $table) {
            $table->string('id', 255);
            $table->string('item_name');
            $table->string('item_sku');
            $table->integer('quantity')->default(0);
            $table->string('uom', 255)->nullable();
            $table->string('uom_name', 255)->nullable();
            $table->text('package_description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=aktif 0 = nonaktif');
            $table->string('last_modified_by', 255)->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->primary(['id']);
            $table->timestamps();
            $table->softDeletes();
            $table->index('item_sku');
            $table->index('status');
        });

        Schema::create('sembako_many', function (Blueprint $table) {
            $table->string('id', 255);
            $table->string('package_id', 255);
            $table->string('item_id', 255);
            $table->timestamps();
            $table->primary(['id']);

            $table->index('package_id');
            $table->index('item_id');

            $table->foreign('package_id')->references('id')->on('sembako_packages');
            $table->foreign('item_id')->references('id')->on('sembako_package_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sembako_many');
        Schema::drop('sembako_package_items');
        Schema::drop('sembako_packages');
    }
}
