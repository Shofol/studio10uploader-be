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
        Schema::create('after_group_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('after_events_id')->constrained('after_events')->onDelete('cascade');
            $table->bigInteger('files_id')->nullable();
            $table->string('type');
            $table->string('media_type')->nullable();
            $table->string('media')->nullable();
            $table->string('duration');
            $table->string('name');
            $table->string('comment');
            $table->string('color');
            $table->string('audio')->nullable();
            $table->timestamp('start_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('after_group_events');
    }
};
