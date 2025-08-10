<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInvoice extends Model
{
    use HasFactory;

    protected $table = 'user_invoices';

    protected $fillable = [
        'user_id', 'invoice_id', 'status', 'amount_due', 'amount_paid', 'currency', 'due_date', 'paid_at', 'data',
    ];

    protected $casts = [
        'data' => 'array',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}


