<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request', function($table)
		{
    		$table->index('is_walker_started');
    		$table->index('is_walker_arrived');
    		$table->index('is_started');
    		$table->index('is_completed');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('request', function($table)
		{
    		$table->dropIndex('request_is_walker_started_index');
    		$table->dropIndex('request_is_walker_started_index');
    		$table->dropIndex('request_is_started_index');
    		$table->dropIndex('request_is_completed_index');
		});
	}

}
