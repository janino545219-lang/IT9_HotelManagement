<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WalkIn extends Model
{
    protected $primaryKey = 'walkin_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'walkin_id',
        'guest_name',
        'phone',
        'num_guests',
        'employee_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'status',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->walkin_id)) {
                $model->walkin_id = (string) Str::uuid();
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function room()
    {
        return $this->belongsTo(\App\Models\Room::class, 'room_id', 'room_id');
    }
}
