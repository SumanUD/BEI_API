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
        $table->string('desktop_video')->nullable();
        $table->string('mobile_video')->nullable();
        $table->text('description')->nullable();
        $table->string('right_image')->nullable();
        $table->json('team_members')->nullable(); // array of team members
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
