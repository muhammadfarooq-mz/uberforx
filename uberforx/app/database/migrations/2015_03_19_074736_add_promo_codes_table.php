<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromoCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('promo_codes', function($t) {
          // auto increment id (primary key)
          $t->increments('id');
          $t->string('coupon_code');
          $t->integer('value');
          $t->integer('type');
          $t->integer('uses');
          $t->integer('state');
          $t->datetime('start_date');
          $t->datetime('expiry');
          // created_at, updated_at DATETIME
          $t->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('promo_codes');
	}

}
