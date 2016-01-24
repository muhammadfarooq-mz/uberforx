<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAlterDatatypeInformationContent extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('information', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE information MODIFY COLUMN content MEDIUMBLOB");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('information', function(Blueprint $table)
		{
			
		});
	}

}
