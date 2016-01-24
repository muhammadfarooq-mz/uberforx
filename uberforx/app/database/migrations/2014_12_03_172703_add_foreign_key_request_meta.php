<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyRequestMeta extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request_meta', function(Blueprint $table)
		{
			DB::statement('alter table request_meta modify request_id int unsigned not null');
			$table->foreign('request_id')->references('id')->on('request')->onDelete('cascade');

			DB::statement('alter table request_meta modify walker_id int unsigned not null');
			$table->foreign('walker_id')->references('id')->on('walker')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('request_meta', function(Blueprint $table)
		{
			$table->dropForeign('request_meta_owner_id_foreign');
			$table->dropForeign('request_meta_walker_id_foreign');
		});
	}

}
