<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guest extends Model
{
    protected $table = 'guests';
    protected $primaryKey = 'guest_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'guest_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    /**
     * Boot method for UUID generation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->guest_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'guest_id', 'guest_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'guest_id', 'guest_id');
    }
}
