<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analysis extends Model
{
    protected $fillable = ['name', 'description'];

    public function labAnalyses()
    {
        return $this->hasMany(LabAnalysis::class);
    }


}
