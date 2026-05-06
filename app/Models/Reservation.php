<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    protected $primaryKey = 'reservation_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'reservation_id',
        'guest_id',
        'room_id',
        'employee_id',
        'check_in_date',
        'check_out_date',
        'num_guests',
        'status',
        'total_amount',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->reservation_id)) {
                $model->reservation_id = (string) Str::uuid();
            }
        });
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'guest_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'reservation_id', 'reservation_id');
    }
}