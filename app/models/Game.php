<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'fizzy_id','pin','is_active','started_at'
    ];
}
