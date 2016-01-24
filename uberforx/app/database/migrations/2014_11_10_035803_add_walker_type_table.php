<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWalkerTypeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('walker_type', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('min_fare')->default(0);
            $table->string('max_size')->default(0);
            $table->integer('is_default');
            $table->double('price_per_unit_distance', 12, 2)->default(0);
            $table->double('price_per_unit_time', 12, 2)->default(0);
            $table->double('base_price', 15, 2)->default(0);
            $table->boolean('is_visible')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('walker_type');
    }

}
