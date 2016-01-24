<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyLedger extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ledger', function(Blueprint $table)
		{
			DB::statement('alter table ledger modify owner_id int unsigned not null');
			$table->foreign('owner_id')->references('id')->on('owner')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ledger', function(Blueprint $table)
		{
			$table->dropForeign('ledger_owner_id_foreign');
		});
	}

}
