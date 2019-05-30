<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];

    public function marks()
    {
      return $this->belongsTo('App\Mark');
    }


}
