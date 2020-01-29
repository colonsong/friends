<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    //

    public function images()
    {
        return $this->hasMany('App\Images');

    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
}
