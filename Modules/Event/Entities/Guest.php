<?php

namespace Modules\Event\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Event\Database\factories\GuestFactory::new();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
