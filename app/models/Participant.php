<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'game_id', 'name', 'points','totalPoints','curruntAnswer'
    ];
}
