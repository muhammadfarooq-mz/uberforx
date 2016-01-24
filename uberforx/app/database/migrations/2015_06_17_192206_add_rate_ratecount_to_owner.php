<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateRateCountToOwner extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('owner', function(Blueprint $table) {
            $table->float('rate', 5, 2)->default(0);
            $table->bigInteger('rate_count')->default(0);
            $table->bigInteger('promo_count')->default(0);
            $table->tinyInteger('is_referee')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('owner', function(Blueprint $table) {
            $table->dropColumn('rate');
            $table->dropColumn('rate_count');
            $table->dropColumn('promo_count');
            $table->dropColumn('is_referee');
        });
    }

}
