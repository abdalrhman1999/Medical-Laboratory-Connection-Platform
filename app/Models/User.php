<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'laboratory_id',
        'name',
        'email',
        'password',
        'phone_number',
        'image',
        'point',
    ];

    public function userAnalyses()
    {
        return $this->hasMany(UserAnalysis::class);
    }
    public function notUsers()
    {
        return $this->hasMany(NotUser ::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
    public function location()
    {
        return $this->hasMany(Location::class);}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
