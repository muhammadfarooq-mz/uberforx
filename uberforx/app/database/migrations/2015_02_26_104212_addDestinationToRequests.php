<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDestinationToRequests extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('request', function(Blueprint $table) {
            $table->double('D_latitude', 15, 8)->default(0);
            $table->double('D_longitude', 15, 8)->default(0);
            $table->integer('security_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('request', function(Blueprint $table) {
            $table->dropColumn('D_latitude');
            $table->dropColumn('D_longitude');
            $table->dropColumn('security_key');
        });
    }

}
