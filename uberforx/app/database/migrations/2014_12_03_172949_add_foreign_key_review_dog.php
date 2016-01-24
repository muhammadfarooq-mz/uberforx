<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReviewDog extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('review_dog', function(Blueprint $table) {
            DB::statement('alter table review_dog modify owner_id int unsigned not null');
            $table->foreign('owner_id')->references('id')->on('owner')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('review_dog', function(Blueprint $table) {
            $table->dropForeign('review_dog_owner_id_foreign');
        });
    }

}
