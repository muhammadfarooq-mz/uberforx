<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateRateCountToProviderTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('walker', function(Blueprint $table) {
            $table->float('rate', 5, 2);
            $table->bigInteger('rate_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('walker', function(Blueprint $table) {
            $table->dropColumn('rate');
            $table->dropColumn('rate_count');
        });
    }

}
