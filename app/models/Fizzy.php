<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fizzy extends Model
{
    protected $fillable = ['title', 'description', 'fizzy_token', 'type', 'publish_at', 'user_id'];

    public function reports()
    {
        return $this->hasMany(new Report());
    }
    public function questions()
    {
        return $this->hasMany(new Question());
    }
}
