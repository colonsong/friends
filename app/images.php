<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class images extends Model
{
    //

    public function profiles()
    {
        return $this->belongsTo('App\Profiles');
    }
}
