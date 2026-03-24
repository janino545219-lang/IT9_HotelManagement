<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'invoice_id',
        'reservation_id',
        'guest_id',
        'subtotal',
        'tax_amount',
        'discount',
        'total_amount',
        'status',
        'issued_at',
        'due_date',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'due_date'  => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->invoice_id)) {
                $model->invoice_id = (string) Str::uuid();
            }
        });
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'guest_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }
}