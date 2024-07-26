<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotUser extends Model
{
    protected $fillable = ['user_id', 'model_user_id', 'not_content','not_date'];


    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function modelUser()
    {
        return $this->belongsTo(ModelUser::class);
    }
}
