<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelUser extends Model
{
    protected $fillable = ['model_name','model_described'];

    public function notUsers()
    {
        return $this->hasMany(NotUser::class);
    }
}
