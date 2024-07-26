<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnlineDistance extends Model
{
    protected $fillable =['user_date_loc_id', 'lab_loc_id', 'distance'];

    public function labLoc()
    {
        return $this->belongsTo(LabLoc::class);
    }

    public function userDateLoc()
    {
        return $this->belongsTo(UserDateLoc::class);
    }
}

