<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('subject_title');
            $table->string('description')->nullable();
            $table->integer('customer_id');
            $table->string('company_name');
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->integer('company_meeting_id')->nullable();
            $table->string('company_meeting_subject_id');
            $table->string('company_meeting_name');
            $table->string('company_meeting_description');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamp('remind_at')->nullable();
            $table->string('location')->nullable();
            $table->string('priority')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('is_complete')->nullable();
            $table->timestamp('is_complete_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar');
    }
}
