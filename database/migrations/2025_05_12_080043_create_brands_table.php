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
    Schema::create('brands', function (Blueprint $table) {
        $table->id();
        $table->json('banner_images')->nullable();  // Store multiple banner images
        $table->string('youtube_link')->nullable();
        $table->text('below_video_text')->nullable();
        $table->json('image_gallery')->nullable();  // Store array of image paths
        $table->json('video_gallery')->nullable();  // Store array of video URLs
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
