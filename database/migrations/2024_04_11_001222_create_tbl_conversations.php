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
        Schema::create('tbl_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->bigInteger('type_id')->unsigned();
            $table->bigInteger('user_created')->unsigned();
            $table->char('status', 1)->default(1)->comment('0: Inactive, 1: Active, 2: Delete');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('tbl_conversation_types');
            $table->foreign('user_created')->references('id')->on('tbl_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_conversations');
    }
};
