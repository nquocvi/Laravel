<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailuresDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'line',
        'attribute',
        'erorr',
        'value',
        'failures_id'
    ];

    public function failures()
    {
        return $this->belongsTo(Failures::class);
    }
}
