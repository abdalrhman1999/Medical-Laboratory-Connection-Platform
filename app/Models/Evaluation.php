<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = ['user_id', 'laboratory_id', 'evaluation'];


    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
