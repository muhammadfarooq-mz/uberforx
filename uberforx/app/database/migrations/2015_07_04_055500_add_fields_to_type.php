<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToType extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('walker_type', function(Blueprint $table) {
            $table->double('price_per_unit_distance', 12, 2)->default(0);
            $table->double('price_per_unit_time', 12, 2)->default(0);
            $table->double('base_price', 15, 2)->default(0);
            $table->dropColumn('min_fare');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('walker_type', function(Blueprint $table) {
            $table->dropColumn('price_per_unit_distance');
            $table->dropColumn('price_per_unit_time');
            $table->dropColumn('base_price');
        });
    }

}
