<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToWalkerServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('walker_services', function(Blueprint $table)
		{
			$table->float('price_per_unit_distance')->default(0);
			$table->float('price_per_unit_time')->default(0);
			$table->float('base_price')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('walker_services', function(Blueprint $table)
		{
			$table->dropColumn('price_per_unit_distance');
			$table->dropColumn('price_per_unit_time');
			$table->dropColumn('base_price');
		});
	}

}
