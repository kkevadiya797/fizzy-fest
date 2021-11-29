<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeComponent extends Model
{
    protected $fillable = ['key', 'title', 'status', 'theme_page'];
}
