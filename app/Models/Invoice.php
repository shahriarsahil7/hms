<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    public const STATUSES = ['unpaid', 'partially_paid', 'paid', 'cancelled'];

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'appointment_id',
        'issue_date',
        'due_date',
        'total_amount',
        'amount_paid',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'total_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function balanceDue(): float
    {
        return round((float) $this->total_amount - (float) $this->amount_paid, 2);
    }

    public function statusBadgeColor(): string
    {
        return match ($this->status) {
            'unpaid' => 'danger',
            'partially_paid' => 'warning',
            'paid' => 'success',
            'cancelled' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Recalculate amount_paid / status from related payments and item totals.
     */
    public function refreshTotals(): void
    {
        $this->total_amount = $this->items()->sum(\Illuminate\Support\Facades\DB::raw('quantity * unit_price'));
        $this->amount_paid = $this->payments()->sum('amount');

        if ($this->amount_paid <= 0) {
            $this->status = 'unpaid';
        } elseif ($this->amount_paid < $this->total_amount) {
            $this->status = 'partially_paid';
        } else {
            $this->status = 'paid';
        }

        $this->save();
    }
}
