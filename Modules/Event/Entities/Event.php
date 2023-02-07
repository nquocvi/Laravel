<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_time',
        'location',
        'description',
        'start_date',
        'end_date',
        'delete',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Event\Database\factories\EventFactory::new();
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
