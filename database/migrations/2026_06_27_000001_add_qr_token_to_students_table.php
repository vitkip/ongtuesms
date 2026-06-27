<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('students', 'qr_token')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('qr_token', 64)->nullable()->unique()->after('password');
            });
        }

        // Populate existing students with unique tokens
        DB::table('students')->whereNull('qr_token')->orderBy('id')->each(function ($student) {
            DB::table('students')
                ->where('id', $student->id)
                ->update(['qr_token' => Str::random(48)]);
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};
