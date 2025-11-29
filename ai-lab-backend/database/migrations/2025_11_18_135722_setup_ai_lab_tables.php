<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // =============================
        // 1. ENUM TYPE (PostgreSQL)
        // =============================
        DB::statement("
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'permit_status') THEN
                    CREATE TYPE permit_status AS ENUM ('pending','accepted','rejected');
                END IF;
            END
            $$;
        ");

        // =============================
        // 2. TABLE: lab_permit_requests
        // =============================
        Schema::create('lab_permit_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('full_name', 200);
            $table->string('study_program', 150)->nullable();
            $table->smallInteger('semester')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 200);
            $table->text('reason');
            $table->enum('status', ['pending','accepted','rejected'])
                  ->default('pending');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
        });

        // =============================
        // 3. TABLE: volunteer_registrations
        // =============================
        Schema::create('volunteer_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 200);
            $table->string('nickname', 100)->nullable();
            $table->string('study_program', 150)->nullable();
            $table->smallInteger('semester')->nullable();
            $table->string('email', 200)->nullable();
            $table->string('phone', 50)->nullable();
            $table->jsonb('areas')->nullable();
            $table->text('skills')->nullable();
            $table->text('motivation')->nullable();
            $table->string('availability', 100)->nullable();
            $table->timestamps();
        });

        // =============================
        // 4. TABLE: admin_actions
        // =============================
        Schema::create('admin_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('action_type', 50);
            $table->string('target_table', 50);
            $table->unsignedBigInteger('target_id');
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
        });

        // =============================
        // 5. TABLE: email_logs
        // =============================
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('to_email', 200);
            $table->string('from_email', 200);
            $table->text('subject')->nullable();
            $table->text('body')->nullable();
            $table->string('related_table', 50)->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('status', 50)->default('queued');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // =============================
// 6. TRIGGERS / FUNCTIONS
// =============================

// 6.a Trigger limit 3 permit per email
DB::statement("
    CREATE OR REPLACE FUNCTION trg_check_permit_limit()
    RETURNS TRIGGER AS $$
    DECLARE
        cnt INTEGER;
    BEGIN
        SELECT COUNT(*) INTO cnt FROM lab_permit_requests WHERE email = NEW.email;

        IF TG_OP = 'INSERT' THEN
            IF cnt >= 3 THEN
                RAISE EXCEPTION 'Max 3 submissions allowed for %', NEW.email;
            END IF;
        END IF;

        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;
");

// DROP trigger harus dipisah!
DB::statement("DROP TRIGGER IF EXISTS trg_lab_permit_limit ON lab_permit_requests");

// CREATE trigger dipisah!
DB::statement("
    CREATE TRIGGER trg_lab_permit_limit
    BEFORE INSERT ON lab_permit_requests
    FOR EACH ROW EXECUTE FUNCTION trg_check_permit_limit()
");


// 6.b Trigger: limit max 5 admins
DB::statement("
    CREATE OR REPLACE FUNCTION trg_limit_admins()
    RETURNS TRIGGER AS $$
    DECLARE cnt INTEGER;
    BEGIN
        IF NEW.role = 'admin' THEN
            SELECT COUNT(*) INTO cnt FROM users WHERE role = 'admin';
            IF cnt >= 5 THEN
                RAISE EXCEPTION 'Max 5 admins allowed.';
            END IF;
        END IF;
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;
");

DB::statement("DROP TRIGGER IF EXISTS trg_users_admin_limit ON users");

DB::statement("
    CREATE TRIGGER trg_users_admin_limit
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION trg_limit_admins()
");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('admin_actions');
        Schema::dropIfExists('volunteer_registrations');
        Schema::dropIfExists('lab_permit_requests');

        DB::statement("DROP TYPE IF EXISTS permit_status CASCADE");
    }
};
