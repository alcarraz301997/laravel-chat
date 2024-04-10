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
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->id();
            // $table->string('user', 20)->unique();
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('email')->unique();
            $table->text('password');
            $table->text('address')->nullable();
            $table->string('phone', 15)->nullable();
            $table->date('birthday');
            $table->text('img_profile')->nullable();
            $table->char('status', 1)->default(1)->comment('0: Inactive, 1: Active, 2: Delete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_user');
    }
};
