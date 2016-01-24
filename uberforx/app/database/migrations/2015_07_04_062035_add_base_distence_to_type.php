<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBaseDistenceToType extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('walker_type', function(Blueprint $table) {
            $table->integer('base_distance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('walker_type', function(Blueprint $table) {
            $table->dropColumn('base_distance');
        });
    }

}
