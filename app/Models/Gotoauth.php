<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gotoauth extends Model
{
    protected $fillable = [
        'organizer_key',
        'client_id',
        'client_secret',
        'access_token',
        'refresh_token',
        'token_type',
        'expires_in',
        'scope',
        'principal',
    ];
}
