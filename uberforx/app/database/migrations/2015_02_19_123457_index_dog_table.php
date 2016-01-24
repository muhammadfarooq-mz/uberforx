<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexDogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dog', function($table)
		{
    		$table->index('name');
    		$table->index('owner_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dog', function($table)
		{
    		$table->dropIndex('dog_name_index');
    		$table->dropIndex('dog_owner_id_index');

		});
	}

}
