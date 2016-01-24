<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFieldsFromWalkerTypeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('walker_type', function(Blueprint $table) {
            $table->dropColumn('price_per_unit_distance');
            $table->dropColumn('price_per_unit_time');
            $table->dropColumn('base_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
