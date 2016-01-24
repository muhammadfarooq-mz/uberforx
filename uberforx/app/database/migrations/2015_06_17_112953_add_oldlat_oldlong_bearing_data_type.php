<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOldLatOldLongBearingDataType extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('walker', function(Blueprint $table) {
            $table->double('old_latitude', 15, 8)->default(0);
            $table->double('old_longitude', 15, 8)->default(0);
            $table->double('bearing', 15, 8)->default(0);
            $table->string('car_model')->default(0);
            $table->string('car_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('walker', function(Blueprint $table) {
            $table->dropColumn('old_latitude');
            $table->dropColumn('old_longitude');
            $table->dropColumn('bearing');
            $table->dropColumn('car_model');
            $table->dropColumn('car_number');
        });
    }

}
