<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable =['user_id', 'laboratory_id' , 'latitude', 'longitude'];

    public function onlineDistances()
    {
        return $this->hasMany(OnlineDistance::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laboratories()
    {
        return $this->hasone(Laboratory::class);
    }

}
