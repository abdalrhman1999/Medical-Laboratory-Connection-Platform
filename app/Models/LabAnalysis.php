<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabAnalysis extends Model
{
    protected $fillable = ['analysis_id', 'laboratory_id', 'cost','point'];

    public function userAnalyses()
    {
        return $this->hasMany(UserAnalysis::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function analysis()
    {
        return $this->belongsTo(Analysis::class);
    }
}

