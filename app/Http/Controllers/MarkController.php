<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mark;

class MarkController extends Controller
{
    //
    public function store(Request $request)
    {
        $validate_data = $request->validate([
          'name'=>'required'
        ],[],['name'=>'لازم تكتب اسم الماركه ']);

        $new_mark =Mark::create([
          'name'=>$request->name,
        ]);


        return response()->json(['new_mark'=>$new_mark],201);
    }


    public function read(Request $request)
    {

      //bug
    $offset = $request->has('offset') ? $request->offset : 0;

    $all_marks = Mark::count();

    $read_mark = Mark::all();


    return response()->json(['marks'=>$read_mark,'count'=>$all_marks],201);

  }

  public function delete_mark(Request $request)
  {
    $id = $request->id;
    $delete_mark = Mark::findOrFail($id);
    $delete_mark->delete();
    return response()->json(['']);
  }

  public function edit_mark(Request $request)
  {

    $validate_data = $request->validate([
      'name'=>'required'
    ],[],['name'=>'لازم تكتب اسم الماركه ']);
    $id = $request->id;
    $edit_mark= Mark::findOrFail($id);
    $edit_mark->name = $request->name;
    $edit_mark->save();

    return response()->json(['new_mark'=>$edit_mark],201);
  }
}
