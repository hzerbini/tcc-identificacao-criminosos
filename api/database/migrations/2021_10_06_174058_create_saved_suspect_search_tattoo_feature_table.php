<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedSuspectSearchTattooFeatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_suspect_search_tattoo_feature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saved_suspect_search_id')->constrained();
            $table->foreignId('tattoo_feature_id')->constrained();
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
        Schema::dropIfExists('saved_suspect_search_tattoo_feature');
    }
}
