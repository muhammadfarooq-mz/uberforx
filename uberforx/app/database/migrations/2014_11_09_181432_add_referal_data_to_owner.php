<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferalDataToOwner extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('owner', function(Blueprint $table)
		{
			$table->integer('referred_by');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('owner', function(Blueprint $table)
		{
			$table->dropColumn('referred_by');
		});
	}

}
