<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    protected $fillable = [
        'title',
        'date',
        'location',
        'note',
        'image',
        'created_by_name',
        'created_by_email'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}