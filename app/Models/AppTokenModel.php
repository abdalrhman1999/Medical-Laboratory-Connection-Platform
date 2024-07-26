<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTokenModel extends Model
{
    use HasFactory;
    protected $table = "app_token";
    protected $fillable = [
        "token",
        "user_id"
    ];
}
