<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Failures extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'failed',
        'detail_failures'
    ];

    public function failuresDetail()
    {
        return $this->hasMany(FailuresDetail::class);
    }
}
