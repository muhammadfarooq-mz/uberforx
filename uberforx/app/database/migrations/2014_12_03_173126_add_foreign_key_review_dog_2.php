<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReviewDog2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('review_dog', function(Blueprint $table)
		{
			DB::statement('alter table review_dog modify walker_id int unsigned not null');
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
		Schema::table('review_dog', function(Blueprint $table)
		{
			$table->dropForeign('review_dog_walker_id_foreign');
		});
	}

}
