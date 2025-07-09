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
        Schema::create('service_pages', function (Blueprint $table) {
            $table->id();
            $table->longText('brand_strategy')->nullable();
            $table->longText('creative')->nullable();
            $table->longText('packaging')->nullable();
            $table->longText('social_media')->nullable();
            $table->longText('digital_media')->nullable();
            $table->longText('seo_website_ecommerce')->nullable();
            $table->longText('mainline_media')->nullable();

            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_pages');
    }
};
