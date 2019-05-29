<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $guarded = [];

    public function customer()
    {
      return $this->belongsTo('App\Customer');
    }

    public function details()
    {
      return $this->hasMany('App\BillDetails');
    }
}
