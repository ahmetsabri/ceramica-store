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
    public function bill_detail()
    {
      return $this->belongsTo('App\BillDeatils');
    }


}
