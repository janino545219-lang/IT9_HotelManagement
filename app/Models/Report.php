<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'report_id',
        'staff_id',
        'report_type',
        'content',
        'report_date',
        'generated_at',
    ];

    protected $casts = [
        'report_date'  => 'date',
        'generated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->report_id)) {
                $model->report_id = (string) Str::uuid();
            }
        });
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }
}
