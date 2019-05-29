<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Mark;

class StockController extends Controller
{
    //

    public function store(Request $request)
    {
      $validate_datac = $request->validate([
        'mark_id'=>'required',
        'filter_id'=>'required',
        'color'=>'required',
        'quantity'=>'required',
        'size'=>'required',
        'price'=>'required',
      ]);

      $mark_id = $request->mark_id;

      $filter_id = $request->filter_id;

      $color=$request->color;

      $price=$request->price;

      $quantity=$request->quantity;

      $size=$request->size;



      $stock = Stock::create([
        'mark_id'=>$mark_id,
        'filter_id'=>$filter_id,
        'color'=>$color,
        'price'=>$price,
        'quantity'=>$quantity,
        'size'=>$size,
      ]);
      return response()->json(['new_stock'=>$stock],201);
    }
    public function read(Request $request)
    {
      $mark = Mark::all();
      $stock = Stock::all();
      return response()->json(['marks'=>$mark,'stock'=>$stock ]);
    }
    public function edit(Request $request)
    {

    }
    public function delete(Request $request)
    {

    }
}
