<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyUserPromoUsed extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('user_promo_used', function(Blueprint $table) {
            DB::statement('ALTER TABLE promo_codes modify coupon_code VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL');
            DB::statement('ALTER TABLE ledger modify referral_code VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_general_cs  NOT NULL');
            $table->foreign('code_id')->references('id')->on('promo_codes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('owner')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('user_promo_used', function(Blueprint $table) {
            $table->dropForeign('user_promo_used_code_id_foreign');
            $table->dropForeign('user_promo_used_user_id_foreign');
        });
    }

}
