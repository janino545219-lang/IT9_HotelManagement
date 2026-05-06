<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Employee extends Model
{
    protected $primaryKey = 'employee_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'employee_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'department',
        'hire_date',
        'salary',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hire_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->employee_id)) {
                $model->employee_id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function walkins()
    {
        return $this->hasMany(WalkIn::class, 'employee_id', 'employee_id');
    }
}
