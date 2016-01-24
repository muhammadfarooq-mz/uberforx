<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyWalkLocation extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('walk_location', function(Blueprint $table) {
            DB::statement('alter table walk_location modify request_id int unsigned not null');
            $table->foreign('request_id')->references('id')->on('request')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('walk_location', function(Blueprint $table) {
            $table->dropForeign('walk_location_request_id_foreign');
        });
    }

}
