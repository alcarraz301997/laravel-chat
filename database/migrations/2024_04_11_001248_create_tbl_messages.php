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
        Schema::create('tbl_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->text('content');
            $table->bigInteger('type_content_id')->unsigned();
            $table->char('status', 1)->default(1)->comment('0: Inactive, 1: Active, 2: Delete');
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('tbl_conversations');
            $table->foreign('user_id')->references('id')->on('tbl_user');
            $table->foreign('type_content_id')->references('id')->on('tbl_content_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_messages');
    }
};
