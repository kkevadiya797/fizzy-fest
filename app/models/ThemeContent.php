<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeContent extends Model
{
    protected $fillable = ['theme_component_id', 'title', 'sub_title', 'description', 'image_url', 'link_url', 'status'];
}
