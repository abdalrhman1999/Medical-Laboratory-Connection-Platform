<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotLab extends Model
{
    protected $fillable =['model_lab_id', 'laboratory_id', 'not_content','not_date'];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function modelLab()
    {
        return $this->belongsTo(ModelLab::class);
    }
}
