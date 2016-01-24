<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReviewWalker extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('review_walker', function(Blueprint $table)
		{
			DB::statement('alter table review_walker modify owner_id int unsigned not null');
			$table->foreign('owner_id')->references('id')->on('owner')->onDelete('cascade');
			DB::statement('alter table review_walker modify walker_id int unsigned not null');
			$table->foreign('walker_id')->references('id')->on('walker')->onDelete('cascade');
			DB::statement('alter table review_walker modify request_id int unsigned not null');
			$table->foreign('request_id')->references('id')->on('request')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('review_walker', function(Blueprint $table)
		{
			$table->dropForeign('review_walker_owner_id_foreign');
			$table->dropForeign('review_walker_walker_id_foreign');
			$table->dropForeign('review_walker_request_id_foreign');
		});
	}

}
