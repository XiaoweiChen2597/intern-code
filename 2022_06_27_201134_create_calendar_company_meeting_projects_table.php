<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarCompanyMeetingProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_company_meeting', function (Blueprint $table) {
            $table->integer('calendar_id');
            $table->integer('company_meeting_id')->unsigned();
            $table->foreign('calendar_id_meeting')->references('id')->on('calendar')->onDelete('cascade');
            $table->foreign('company_meeting_id')->references('id')->on('company_meeting_projects')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_company_meeting');
    }
}
