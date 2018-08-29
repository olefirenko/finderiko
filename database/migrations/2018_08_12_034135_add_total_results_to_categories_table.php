<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalResultsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('total_results')->nullable()->after('is_popular');
            $table->integer('ahrefs_difficulty')->nullable()->after('is_popular');
            $table->integer('ahrefs_volume')->nullable()->after('is_popular');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
