<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateInvestorTable.
 */
class CreateInvestorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('investor_name', 150)->nullable();
            $table->string('phone', 20);
            $table->string('email', 150);
            $table->string('category_id', 50)->nullable()->comment('uuid INVESTOR_CATEGORIES');
            $table->string('category_slug')->nullable()->comment('slug INVESTOR_CATEGORIES');
            $table->string('category_name')->nullable()->comment('name INVESTOR_CATEGORIES');
            $table->string('address', 255)->nullable()->comment('alamat rumah / kantor');
            $table->string('donate_id', 50)->nullable()->comment('uuid DONATION_CATEGORIES');
            $table->string('donate_category', 191)->nullable()->comment('slug DONATION_CATEGORIES eg: (logistik, non-medis)');
            $table->string('donate_category_name', 191)->nullable()->comment('name DONATION_CATEGORIES eg: (Non medis, Logistik)');
            $table->string('donate_status', 191)->nullable()->comment('slug dari DONATE_STATUS eg: (pending, verified)');
            $table->string('donate_status_name', 191)->nullable()->comment('name dari DONATE_STATUS eg: (pending, Verified)');
            $table->string('invoice_number', 50)->comment('nomor invoice yang digenerate oleh sistem');
            $table->string('attachment_id', 50)->nullable()->comment('uuid dokumen pernyataan / bukti transfer');
            $table->string('profile_picture', 191)->nullable()->comment('full path logo jika perusahaan');
            $table->tinyInteger('show_name')->default(0)->comment('1=ditampilkan 0 = tidak ditampilkan');
            $table->tinyInteger('award_claim')->default(0)->comment('1=piagam sudah diterima 0 = piagam belum diterima');
            $table->dateTime('donate_date')->comment('tanggal donasi dibuat');
            $table->string('last_modified_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
            $table->primary(['id']);

            $table->index('donate_category');
            $table->index('phone');
            $table->index('email');
            $table->index('donate_status');
            $table->index('donate_date');
            $table->index('invoice_number');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('investor_items', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('investor_id', 50);
            $table->string('investor_name', 150)->nullable();
            $table->string('investor_phone', 20);
            $table->string('investor_email', 150);
            $table->string('donate_category', 50)->comment('slug dari DONATION_CATEGORIES');

            //jika sembako
            $table->string('item_package_id', 50)->nullable()->comment('* Wajib diisi jika DONATION_CATEGORIES adalah logistik/medis/non-medis');
            $table->string('item_package_sku', 100)->nullable()->comment('slug item yang didonasikan');
            $table->string('item_package_name', 50)->nullable()->comment('nama item yang didonasikan');

            //jika medis
            $table->string('item_uom_slug', 150)->nullable()->comment('slug uom');
            $table->string('item_uom_name', 100)->nullable()->comment('nama uom');

            $table->integer('quantity')->default(0);

            //jika tunai
            $table->string('bank_id', 50)->nullable()->comment('uuid bank');
            $table->string('bank_name', 150)->nullable()->comment('Nama Bank');
            $table->string('bank_account', 80)->nullable()->comment('Rekening atas nama');
            $table->string('bank_number', 30)->nullable()->comment('Nomor Rekening');
            $table->double('amount')->nullable()->comment('nilai mata uang yang didonasikan, nomor harus unik seperti eccomerce');

            $table->string('last_modified_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();

            $table->primary(['id']);
            $table->index('quantity');
            $table->index('donate_category');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('investor_id')->references('id')->on('investors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('investor_items');
        Schema::drop('investors');
    }
}
