<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceWalkIdToRequestId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('review_dog', function(Blueprint $table)
		{
			$table->dropColumn('walk_id');
			$table->integer('request_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('review_dog', function(Blueprint $table)
		{
			$table->integer('walk_id');
			$table->dropColumn('request_id');
		});
	}

}
