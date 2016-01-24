<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexWalkerServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('walker_services', function($table)
		{
    		$table->index('provider_id');
    		$table->index('type');
    		$table->index('price_per_unit_distance');
    		$table->index('price_per_unit_time');
    		$table->index('base_price');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('walker_services', function($table)
		{
    		$table->dropIndex('walker_services_provider_id_index');
    		$table->dropIndex('walker_services_type_index');
    		$table->dropIndex('walker_services_price_per_unit_distance_index');
    		$table->dropIndex('walker_services_price_per_unit_time_index');
    		$table->dropIndex('walker_services_base_price_index');
		});
	}

}
