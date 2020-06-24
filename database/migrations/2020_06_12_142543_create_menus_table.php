<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->string('id', 255);
            $table->string('parent_id', 255)->nullable();
            $table->string('menu_title', 150)->unique();
            $table->string('slug')->unique()->nullable();
            $table->string('url', 255)->unique()->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->integer('menu_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->index('menu_title');
            $table->index('slug');
            $table->index('url');
            $table->index('is_active');
            $table->index('menu_order');
        });

        Schema::create('menu_roles', function (Blueprint $table) {
            $table->string('id', 255);
            $table->string('role_id', 255);
            $table->string('menu_id', 255);
            $table->tinyInteger('is_enabled')->default(1)->comment('1 = Cannot delete by program');
            $table->text('access')->nullable();
            $table->timestamps();
            $table->primary('id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('menu_id')->references('id')->on('menus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_roles');
        Schema::dropIfExists('menus');
    }
}
