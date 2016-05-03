<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('candidates', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name')->index();
			$table->string('alias')->unique();
			$table->integer('elective_position_id')->unsigned()->nullable()->index();
            $table->timestamps();
			$table->foreign('elective_position_id')->references('id')->on('elective_positions')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('candidates');
	}

}
