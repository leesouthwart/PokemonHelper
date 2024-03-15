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
        Schema::create('currency_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id_1');
            $table->foreignId('currency_id_2');
            $table->decimal('conversion_rate', 12, 6);
            $table->timestamps();

            $table->foreign('currency_id_1')->references('id')->on('currencies');
            $table->foreign('currency_id_2')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_conversions');
    }
};
