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
        Schema::create('group_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('before_events_id')->constrained('before_events')->onDelete('cascade');
            $table->bigInteger('files_id')->nullable();
            $table->string('type');
            $table->string('media_type');
            $table->string('media');
            $table->string('duration');
            $table->string('name');
            $table->string('comment');
            $table->string('color');
            $table->string('audio');
            $table->timestamp('start_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_events');
    }
};
