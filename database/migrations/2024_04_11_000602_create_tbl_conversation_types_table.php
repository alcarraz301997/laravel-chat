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
        Schema::create('tbl_conversation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->char('status', 1)->default(1)->comment('0: Inactive, 1: Active, 2: Delete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_conversation_types');
    }
};
