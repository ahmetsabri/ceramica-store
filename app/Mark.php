<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{

  protected $guarded = [];

    public function stock()
    {
        return $this->belongsTo('App\Stock');
    }
}
