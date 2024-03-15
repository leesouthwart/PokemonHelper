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
        // populate the currency table with GBP JPY AUD and USD
        DB::table('currencies')->insert([
            ['name' => 'British Pound', 'symbol' => '£', 'code' => 'GBP'],
            ['name' => 'Japanese Yen', 'symbol' => '¥', 'code' => 'JPY'],
            ['name' => 'Australian Dollar', 'symbol' => '$', 'code' => 'AUD'],
            ['name' => 'United States Dollar', 'symbol' => '$', 'code' => 'USD']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('currencies')->truncate();
    }
};
