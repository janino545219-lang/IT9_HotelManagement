<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }
}
