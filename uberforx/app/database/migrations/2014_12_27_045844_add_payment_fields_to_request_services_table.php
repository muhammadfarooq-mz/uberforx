<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentFieldsToRequestServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request_services', function(Blueprint $table)
		{
			$table->float('base_price')->default(0);
			$table->float('distance_cost')->default(0);
			$table->float('time_cost')->default(0);
			$table->float('total')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('request_services', function(Blueprint $table)
		{
			$table->dropColumn('base_price');
			$table->dropColumn('distance_cost');
			$table->dropColumn('time_cost');
			$table->dropColumn('total');
		});
	}

}
