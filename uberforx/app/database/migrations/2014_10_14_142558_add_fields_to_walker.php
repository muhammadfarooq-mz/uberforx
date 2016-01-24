<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldsToWalker extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('walker', function(Blueprint $table)
		{
			$table->string('bio')->after('picture');
			$table->string('address')->after('bio');
			$table->string('state')->after('address');
			$table->string('country')->after('state');
			$table->string('zipcode')->after('country');

			$table->string('device_token')->after('zipcode');
			$table->enum('device_type', array('android', 'ios'))->after('device_token');
			$table->enum('login_by', array('manual', 'facebook', 'google'))->after('device_type');
			$table->string('social_unique_id')->after('login_by');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('walker', function(Blueprint $table)
		{
			$table->drop('device_token');
			$table->drop('device_type');
			$table->drop('bio');
		});
	}

}
