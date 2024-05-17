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
        Schema::create('ebay_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price', 8, 2)->nullable();
            $table->unsignedBigInteger('quantity')->default(1);
            $table->string('image_1');
            $table->string('image_2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ebay_listings');
    }
};
