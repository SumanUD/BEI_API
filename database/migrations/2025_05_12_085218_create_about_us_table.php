<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('about_us', function (Blueprint $table) {
        $table->id();
        $table->string('banner_video')->nullable();
        $table->string('about_image')->nullable();
        $table->string('about_bg_image')->nullable();
        $table->json('team_members')->nullable(); // array of {image, description}
        $table->string('email')->nullable();
        $table->string('linkedin')->nullable();
        $table->json('about_gallery')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
