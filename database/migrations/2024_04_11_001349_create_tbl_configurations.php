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
        Schema::create('tbl_configurations', function (Blueprint $table) {
            $table->id();
            $table->integer('configuration_1');
            $table->string('configuration_2', 100);
            $table->bigInteger('user_id')->unsigned();
            $table->char('status', 1)->default(1)->comment('0: Inactive, 1: Active, 2: Delete');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('tbl_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_configurations');
    }
};
