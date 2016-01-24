<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaIdInWalk extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('walk', function(Blueprint $table)
		{
			$table->integer('meta_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('walk', function(Blueprint $table)
		{
			$table->dropColumn('meta_id');
		});
	}

}
