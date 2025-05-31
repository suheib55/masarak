<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'date',
        'location',
        'note',
        'image',
        'user_id',
        'created_by_name',
        'created_by_email'
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}