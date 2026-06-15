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
        // 1. academic_years
        if (!Schema::hasTable('academic_years')) {
            Schema::create('academic_years', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('year', 9)->unique();
                $table->enum('status', ['active', 'inactive'])->default('active');
            });
        }

        // 2. majors
        if (!Schema::hasTable('majors')) {
            Schema::create('majors', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('name', 100);
                $table->string('code', 10)->nullable();
                $table->text('description')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
            });
        }

        // 3. users
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('username', 50)->unique();
                $table->string('password', 255);
                $table->enum('role', ['admin', 'user'])->default('user');
                $table->string('full_name', 100)->nullable();
                $table->string('email', 100)->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
                $table->timestamp('last_login')->nullable();
                $table->boolean('is_active')->default(true);
            });
        }

        // 4. settings
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('key', 100)->unique();
                $table->text('value')->nullable();
                $table->text('description')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        // 5. system_logs
        if (!Schema::hasTable('system_logs')) {
            Schema::create('system_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('level', 20);
                $table->text('message');
                $table->integer('user_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('context')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 6. students
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('student_id', 20)->nullable();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->enum('gender', ['ພຣະ', 'ສ.ນ', 'ຊາຍ', 'ຍິງ', 'ອຶ່ນໆ']);
                $table->date('dob');
                $table->string('email', 100)->nullable();
                $table->string('phone', 20)->nullable();
                $table->string('village', 100)->nullable();
                $table->string('district', 100)->nullable();
                $table->string('province', 100)->nullable();
                $table->enum('accommodation_type', ['ຫາວັດໃຫ້', 'ມີວັດຢູ່ແລ້ວ'])->default('ມີວັດຢູ່ແລ້ວ');
                $table->string('photo', 255)->nullable();
                $table->datetime('registered_at')->useCurrent();
                $table->timestamp('created_at')->useCurrent();
                $table->integer('major_id')->nullable();
                $table->integer('academic_year_id')->nullable();
                $table->string('previous_school', 255)->nullable();
                $table->string('password', 255)->nullable();
            });
        }

        // 7. curriculum
        if (!Schema::hasTable('curriculum')) {
            Schema::create('curriculum', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('major_id');
                $table->integer('academic_year_id');
                $table->string('curriculum_name', 255);
                $table->string('curriculum_code', 50)->unique();
                $table->integer('total_credits')->default(120);
                $table->decimal('minimum_gpa', 3, 2)->default(2.00);
                $table->integer('duration_years')->default(4);
                $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        // 8. subjects
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('subject_code', 20);
                $table->string('subject_name', 255);
                $table->string('subject_name_en', 255)->nullable();
                $table->integer('credits')->default(3);
                $table->integer('theory_hours')->default(0);
                $table->integer('practical_hours')->default(0);
                $table->integer('major_id');
                $table->integer('semester')->default(1);
                $table->integer('year_level')->default(1);
                $table->integer('prerequisite_subject_id')->nullable();
                $table->text('description')->nullable();
                $table->text('learning_outcomes')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        // 9. curriculum_subjects
        if (!Schema::hasTable('curriculum_subjects')) {
            Schema::create('curriculum_subjects', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('curriculum_id');
                $table->integer('subject_id');
                $table->enum('subject_type', ['required', 'elective', 'free_elective'])->default('required');
                $table->integer('year_level');
                $table->integer('semester');
                $table->integer('sort_order')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->unique(['curriculum_id', 'subject_id'], 'unique_curriculum_subject');
            });
        }

        // 10. enrollments
        if (!Schema::hasTable('enrollments')) {
            Schema::create('enrollments', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('student_id');
                $table->integer('subject_id');
                $table->integer('academic_year_id');
                $table->integer('semester');
                $table->date('enrollment_date');
                $table->enum('status', ['enrolled', 'dropped', 'completed', 'failed'])->default('enrolled');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        // 11. grades
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('enrollment_id');
                $table->integer('student_id');
                $table->integer('subject_id');
                $table->integer('academic_year_id');
                $table->integer('semester');
                $table->decimal('midterm_score', 5, 2)->nullable();
                $table->decimal('final_score', 5, 2)->nullable();
                $table->decimal('assignment_score', 5, 2)->nullable();
                $table->decimal('participation_score', 5, 2)->nullable();
                $table->decimal('practical_score', 5, 2)->nullable();
                $table->decimal('project_score', 5, 2)->nullable();
                $table->decimal('total_score', 5, 2)->nullable();
                $table->decimal('percentage', 5, 2)->nullable();
                $table->string('letter_grade', 2)->nullable();
                $table->decimal('grade_point', 3, 2)->nullable();
                $table->integer('credits_earned')->default(0);
                $table->enum('status', ['in_progress', 'completed', 'failed', 'incomplete', 'withdrawn'])->default('in_progress');
                $table->text('remarks')->nullable();
                $table->integer('graded_by')->nullable();
                $table->timestamp('graded_at')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        // 12. invoices
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('invoice_number', 50)->unique();
                $table->integer('student_id');
                $table->date('date');
                $table->decimal('card_fee', 10, 2)->default(20000.00)->nullable();
                $table->decimal('photo_fee', 10, 2)->default(20000.00)->nullable();
                $table->decimal('email_fee', 10, 2)->default(50000.00)->nullable();
                $table->decimal('tuition_fee', 10, 2)->default(0.00)->nullable();
                $table->decimal('total_amount', 10, 2);
                $table->string('bank_account_number', 50)->default('01452026000028')->nullable();
                $table->string('student_email', 255)->nullable();
                $table->string('parent_email', 255)->nullable();
                $table->string('qr_code_path', 255)->nullable();
                $table->enum('payment_status', ['unpaid', 'paid', 'cancelled'])->default('unpaid')->nullable();
                $table->date('payment_date')->nullable();
                $table->text('notes')->nullable();
                $table->integer('created_by')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('curriculum_subjects');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('curriculum');
        Schema::dropIfExists('students');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('users');
        Schema::dropIfExists('majors');
        Schema::dropIfExists('academic_years');
    }
};
