<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarangaysTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('barangays', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name')->index();
			$table->integer('town_id')->unsigned()->nullable()->index();
			$table->foreign('town_id')->references('id')->on('town')->onDelete('cascade');
            $table->timestamps();
			$table->unique(['name','town_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('barangays');
	}

}
