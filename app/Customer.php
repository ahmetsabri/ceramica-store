<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  protected $guarded = [];

  public function bill()
  {
    return $this->hasOne('App\Bill');
  }


  
}
