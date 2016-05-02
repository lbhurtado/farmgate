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
			$table->string('name')->unique();
			$table->string('precincts');
			$table->integer('registered_voters');
			$table->integer('contact_id')->unsigned()->nullable();
			$table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null');
			$table->integer('town_id')->unsigned()->nullable();
			$table->foreign('town_id')->references('id')->on('towns')->onDelete('set null');
            $table->timestamps();
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
