<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElectionresultsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('election_results', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('cluster_id')->unsigned()->index()->nullable();
			$table->foreign('cluster_id')->references('id')->on('cluster')->onDelete('cascade');
			$table->integer('candidate_id')->unsigned()->index()->nullable();
			$table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
			$table->integer('votes')->unsigned();
            $table->timestamps();
			$table->unique(['cluster_id', 'candidate_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('election_results');
	}

}
