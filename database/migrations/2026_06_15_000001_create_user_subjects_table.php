<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_subjects')) {
            Schema::create('user_subjects', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('user_id');
                $table->integer('subject_id');
                $table->timestamp('created_at')->useCurrent();
                $table->unique(['user_id', 'subject_id'], 'unique_user_subject');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subjects');
    }
};
