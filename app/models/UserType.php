<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $fillable = [
        'title','url','group_id','image_id','html_text','description','status'
    ];
}
