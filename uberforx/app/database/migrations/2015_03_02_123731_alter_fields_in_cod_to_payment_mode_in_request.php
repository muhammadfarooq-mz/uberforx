<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInCodToPaymentModeInRequest extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request', function(Blueprint $table)
		{
			$table->dropColumn('cod');
			$table->integer('payment_mode')->default(0);
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
			$table->dropColumn('payment_mode');
		});
	}

}
