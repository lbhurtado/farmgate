<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollingplacesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('polling_places', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name')->index();
			$table->integer('barangay_id')->unsigned()->nullable()->index();
//			$table->foreign('barangay_id')->references('id')->on('barangay')->onDelete('cascade');
            $table->timestamps();
			$table->unique(['name', 'barangay_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('polling_places');
	}

}
