<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromoCodeToRequestTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('request', function(Blueprint $table) {
            $table->float('promo_payment', 8, 2)->default(0);
            $table->string('promo_code');
            $table->integer('promo_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('request', function(Blueprint $table) {
            $table->dropColumn('promo_payment');
            $table->dropColumn('promo_code');
            $table->dropColumn('promo_id');
        });
    }

}
