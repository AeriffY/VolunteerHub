<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{

    //one to many
    public function registrations(){
        return $this->hasMany(Registration::class);
    }
}
