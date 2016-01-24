<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldsToOwner extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('owner', function(Blueprint $table)
		{
			$table->string('picture')->after('email');
			$table->string('country')->after('state');
			$table->string('device_token')->after('token_expiry');
			$table->enum('device_type', array('android', 'ios'))->after('device_token');
			$table->string('bio')->after('picture');
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
		Schema::table('owner', function(Blueprint $table)
		{
			$table->drop('device_token');
			$table->drop('device_type');
			$table->drop('bio');
			$table->drop('login_by');
			$table->drop('social_unique_id');
		});
	}

}
