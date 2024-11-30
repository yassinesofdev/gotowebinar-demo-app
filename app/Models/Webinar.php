<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{

    protected $fillable = ['subject', 'description', 'startTime', 'endTime', 'type' , 'webinarKey' , 'recurrenceKey'];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

}
