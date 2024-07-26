<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAnalysis extends Model
{
    use HasFactory;

    protected $fillable =['user_id', 'lab_analysis_id' , 'result' , 'min' , 'max'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labAnalysis()
    {
        return $this->belongsTo(LabAnalysis::class);
    }
}
