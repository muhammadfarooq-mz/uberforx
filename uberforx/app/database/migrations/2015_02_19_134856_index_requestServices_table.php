<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexRequestServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request_services', function($table)
		{
    		$table->index('request_id');
    		$table->index('type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('request_services', function($table)
		{
    		$table->dropIndex('request_services_request_id_index');
    		$table->dropIndex('request_services_type_index');
		});
	}

}
