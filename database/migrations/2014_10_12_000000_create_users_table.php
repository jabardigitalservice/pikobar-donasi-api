<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersAccessTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->nullable()->index();
            $table->string('token')->nullable()->index();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('users', function (Blueprint $table) {

            $table->string('id', 50);
            $table->string('username', 150);
            $table->string('email')->unique();
            $table->string('first_name', 150)->nullable();
            $table->string('last_name', 150)->nullable();
            $table->string('gender', 20)->comment('male, female, other only')->nullable();
            $table->string('password');
            $table->string('avatar', 50)->nullable();
            $table->tinyInteger('active')->default(1)->comment('0 = Not Active, 1 = Active');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('username');
            $table->index('gender');

            $table->primary('id');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('role_name', 150)->unique();
            $table->string('slug', 191)->unique();
            $table->text('description')->nullable();
            $table->tinyInteger('is_active')->default(1)->comment('0 = Not Active, 1 = Active');
            $table->tinyInteger('is_default')->default(1)->comment('1 = Cannot delete by program');
            $table->timestamps();
            $table->softDeletes();

            $table->index('role_name');
            $table->primary('id');
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('name');
            $table->string('guard_name');

            $table->primary('id');
            $table->timestamps();
        });

        Schema::create('user_role', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('user_id', 50);
            $table->string('role_id', 50);

            $table->primary('id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('permission_roles', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('role_id', 50);
            $table->string('permission_id', 50);

            $table->primary('id');
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('permission_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_resets');
        Schema::drop('roles');
        Schema::drop('user_role');
        Schema::drop('users');
    }
}
