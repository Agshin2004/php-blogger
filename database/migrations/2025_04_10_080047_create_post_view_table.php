<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table) {
            // One logged in user can view post once, one guest user can view post once per ip
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->ipAddress('ip_address')->nullable();

            // if same post_id and user_id to be added to DB, constraint won't let it 
            $table->unique(['post_id', 'user_id']); // preventing duplicate views by single user
            $table->unique(['post_id', 'ip_address']); // preventing duplicate views by same IP

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_view');
    }
};
