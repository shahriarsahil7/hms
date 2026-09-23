<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnosis extends Model
{
    use HasFactory;

    public const SEVERITIES = ['mild', 'moderate', 'severe', 'critical'];

    protected $fillable = [
        'medical_record_id',
        'diagnosis_name',
        'icd_code',
        'description',
        'severity',
    ];

    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function severityBadgeColor(): string
    {
        return match ($this->severity) {
            'mild' => 'success',
            'moderate' => 'warning',
            'severe' => 'danger',
            'critical' => 'dark',
            default => 'secondary',
        };
    }
}
