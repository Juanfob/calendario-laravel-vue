<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('calendar_events', function (Blueprint $table) {
		    $table->increments('id');
		    $table->unsignedInteger('user_id');
		    $table->foreign('user_id')->references('id')->on('users');
		    $table->enum('type', ['success', 'processing', 'default', 'error', 'warning'])->default('success');
		    $table->string('content', 200);
		    $table->tinyInteger('day', false, true);
		    $table->string('hour', 6);
		    $table->tinyInteger('month', false, true);
		    $table->mediumInteger('year', false, true);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_events');
    }
}
