<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $primaryKey = 'room_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'room_id',
        'room_number',
        'room_type',
        'floor_number',
        'price_per_night',
        'capacity',
        'status',
        'amenities',
        'image',
        'is_active',
    ];
}
