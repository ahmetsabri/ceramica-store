<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  protected $guarded = [];

  public function bills()
  {
    return $this->hasMany('App\Bill');
  }



}
