<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebay_listings', function (Blueprint $table) {
            $table->string('search_phrase')->nullable()->after('image_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebay_listings', function (Blueprint $table) {
            $table->dropColumn('search_phrase');
        });
    }
};
