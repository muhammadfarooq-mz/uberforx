<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyRequest extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request', function(Blueprint $table)
		{
			DB::statement('alter table request modify owner_id int unsigned not null');
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
		Schema::table('request', function(Blueprint $table)
		{
			$table->dropForeign('request_owner_id_foreign');
		});
	}

}
