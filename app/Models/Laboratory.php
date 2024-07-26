<?php
 namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

 class Laboratory extends Model
 {
     protected $fillable = ['lab_name', 'lab_information', 'lab_number','score'];

     public function labAnalyses()
     {
         return $this->hasMany(LabAnalysis::class);
     }

     public function evaluations()
     {
         return $this->hasMany(Evaluation::class);
     }

     public function appointments()
     {
         return $this->hasMany(Appointment::class);
     }

     public function Location()
     {
         return $this->belongsTo(Location::class);
     }

     public function notLab()
     {
         return $this->hasMany(NotLab::class);
     }

     public function user()
     {
         return $this->hasMany(User::class);
     }


 }