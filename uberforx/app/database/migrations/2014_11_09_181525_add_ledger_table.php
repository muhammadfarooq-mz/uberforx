<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLedgerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ledger', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('owner_id');
			$table->string('referral_code');
			$table->integer('total_referrals');
			$table->float('amount_earned');
			$table->float('amount_spent');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ledger');
	}

}
