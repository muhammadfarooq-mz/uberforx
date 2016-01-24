<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexWalkerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('walker', function($table)
		{
    		$table->index('email');
    		$table->index('first_name');
    		$table->index('last_name');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('walker', function($table)
		{
    		$table->dropIndex('walker_email_index');
    		$table->dropIndex('walker_first_name_index');
    		$table->dropIndex('walker_last_name_index');
		});
	}

}
