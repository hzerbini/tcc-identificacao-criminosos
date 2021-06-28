<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTattooTattooFeatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tattoo_tattoo_feature', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('tattoo_id')->constrained();
            $table->foreignId('tattoo_feature_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tattoo_tattoo_feature');
    }
}
