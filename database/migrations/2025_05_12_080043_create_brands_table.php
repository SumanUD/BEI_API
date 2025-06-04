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
        $table->text('brand_name')->nullable();
        $table->text('brand_logo')->nullable();
        $table->text('below_video_text')->nullable();
        $table->json('banner_images')->nullable();
        $table->json('youtube_link')->nullable();
        $table->json('image_gallery')->nullable();
        $table->json('video_gallery')->nullable();
        $table->json('video_gallery_video')->nullable();

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
