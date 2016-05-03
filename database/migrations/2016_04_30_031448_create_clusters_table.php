<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClustersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clusters', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name')->index();
			$table->string('precincts')->index();
			$table->integer('registered_voters');
			$table->integer('contact_id')->unsigned()->nullable();
			$table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null');
			$table->integer('town_id')->unsigned()->nullable();
			$table->foreign('town_id')->references('id')->on('towns')->onDelete('set null');
			$table->integer('polling_place_id')->unsigned()->nullable()->index();
			$table->foreign('polling_place_id')->references('id')->on('polling_place')->onDelete('set null');
            $table->timestamps();
			$table->unique(['name', 'town_id']);
			$table->unique(['precincts', 'town_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clusters');
	}

}
