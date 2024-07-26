<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    protected $fillable =['user_id', 'role_id', 'appointment_date'];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function analyse()
    {
        return $this->belongsTo(Analysis::class);
    }
}

