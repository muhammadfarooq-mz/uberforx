<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReviewDog3 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('review_dog', function(Blueprint $table)
		{
			DB::statement('alter table review_dog modify request_id int unsigned not null');
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
		Schema::table('review_dog', function(Blueprint $table)
		{
			$table->dropForeign('review_dog_request_id_foreign');
		});
	}

}
