<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistanceWalkLocation extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('walk_location', function(Blueprint $table) {
            $table->float('distance', 8, 3);
            $table->double('bearing', 15, 8)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('walk_location', function(Blueprint $table) {
            $table->dropColumn('distance');
            $table->dropColumn('bearing');
        });
    }

}
