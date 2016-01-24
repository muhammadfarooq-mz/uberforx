<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyDog extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dog', function(Blueprint $table)
		{
			DB::statement('alter table dog modify owner_id int unsigned not null');
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
		Schema::table('dog', function(Blueprint $table)
		{
			$table->dropForeign('dog_owner_id_foreign');
		});
	}

}
