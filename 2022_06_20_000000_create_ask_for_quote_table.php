<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAskforQuote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ask_for_quote', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ask_for_quote_company_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->integer('product_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('company_product_id')->unsigned(); 
            $table->integer('quantity');
            $table->integer('price')
            $table->string('product_name');
            $table->string('product_description');
            $table->string('product_image');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('is_comeplete')->nullable();
            $table->timestamp('is_complete_at')->nullable();
            $table->softDeletes();

            $table->foreign('company_product_id')->reference('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ask_for_quote');
    }
}
