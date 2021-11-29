<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'players','game_id','fizzy_id','user_id'
    ];
    public function myGame()
    {
        return $this->belongsTo(new Game());
    }
}
