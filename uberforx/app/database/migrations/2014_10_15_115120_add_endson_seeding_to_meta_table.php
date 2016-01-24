<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndsonSeedingToMetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('schedule_meta', function(Blueprint $table)
		{
			$table->float('started_on');
			$table->float('seeding_status');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('schedule_meta', function(Blueprint $table)
		{
			$table->dropColumn('started_on');
			$table->dropColumn('seeding_status');
		});
	}

}
