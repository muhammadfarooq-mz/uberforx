<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentFiledsToRequest extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request', function(Blueprint $table)
		{
			$table->float('distance');
			$table->float('time');
			$table->float('base_price');
			$table->float('distance_cost');
			$table->float('time_cost');
			$table->float('total');
			$table->integer('is_paid');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('request', function(Blueprint $table)
		{
			$table->dropColumn('distance');
			$table->dropColumn('time');
			$table->dropColumn('base_price');
			$table->dropColumn('distance_cost');
			$table->dropColumn('time_cost');
			$table->dropColumn('total');
			$table->dropColumn('is_paid');
		});
	}

}
