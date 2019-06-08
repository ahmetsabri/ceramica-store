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
        'dimension'=>'required',
      ]);

      $mark_id = $request->mark_id;

      $mark_name = $request->mark_name;

      $filter_id = $request->filter_id;

      $color=$request->color;

      $price=$request->price;

      $quantity=$request->quantity;

      $size=$request->size;

      $mark_name = $request->mark_name;

      $dimension = $request->dimension;

      $stock = Stock::create([
        'mark_id'=>$mark_id,
        'filter_id'=>$filter_id,
        'mark_name'=>$mark_name,
        'color'=>$color,
        'price'=>$price,
        'quantity'=>$quantity,
        'size'=>$size,
        'mark_name'=>$mark_name,
        'dimension'=>$dimension,
      ]);
      return response()->json(['new_stock'=>$stock],201);
    }
    public function read(Request $request)
    {
      $mark = Mark::with('stock')->get();
      $stock = Stock::with('marks')->get();

      return response()->json(['marks'=>$mark,'stock'=>$stock ]);

    }
    public function edit_stock(Request $request)
    {
      $id=$request->id;
      $validatedata = $request->validate([
        'mark_id'=>'required',
        'filter_id'=>'required',
        'color'=>'required',
        'size'=>'required',
        'price'=>'required',
        'quantity'=>'required',
      ]);
      $edit_stock = Stock::findOrFail($id);
      $edit_stock->mark_id = $request->mark_id;
      $edit_stock->mark_name = Mark::find($request->mark_id)->name;
      $edit_stock->filter_id = $request->filter_id;
      $edit_stock->color = $request->color;
      $edit_stock->size = $request->size;
      $edit_stock->price = $request->price;
      $edit_stock->quantity = $request->quantity;
      $edit_stock->dimension = $request->dimension;
      $edit_stock->save();
      return response()->json(['new_stock'=>$edit_stock],201);
    }

    public function delete_stock(Request $request)
    {
      $id = $request->id;
      $delete_stock = Stock::findOrFail($id);
      $delete_stock->delete();
      return response()->json([''],203);
    }

    public function get_stock(Request $request)
    {
      $id = $request->id;

      $get_stock = Mark::findOrFail($id)->stock;

      return response()->json(['stock'=>$get_stock],201);
    }
}
