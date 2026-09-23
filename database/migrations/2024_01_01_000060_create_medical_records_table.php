<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->date('visit_date');
            $table->text('chief_complaint')->nullable();
            $table->text('notes')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->decimal('temperature_celsius', 4, 1)->nullable();
            $table->unsignedSmallInteger('pulse_bpm')->nullable();
            $table->decimal('weight_kg', 5, 1)->nullable();
            $table->decimal('height_cm', 5, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
