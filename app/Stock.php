<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];

    public function marks()
    {
      return $this->hasMany('App\Mark');
    }

    public function filters()
    {
      return $this->hasMany('App\Filter');
    }

}
