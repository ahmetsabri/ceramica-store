<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Stock;
use App\Customer;
use App\BillDetails;

class BillController extends Controller
{
    //

    public function store(Request $request)
    {
      $validate_datac = $request->validate([
        'stock'=>'required',
        'total'=>'required',
        'paid'=>'required',
        'remain'=>'required',

      ]);




      // Customer data
      $customer = Customer::create([
        'name'=>$request->name,
        'address'=>$request->address,
        'phone'=>$request->phone,

      ]);


      $remain = ($request->total) - ($request->paid) ;
      //Bill data
      $bill = Bill::create([
        'customer_id'=>$customer->id,
        'paid'=>$request->paid,
        'total'=>$request->total,
        'remain'=>$request->remain,
      ]);

      $b_id = $bill->id;

            /* LOOP
            'remain'=>$request->remain,
                   sotck_ids,
                    quantity,
            */
            for ($i=0; $i < count($request->stock); $i++) {

              //Bill details
              $bill = BillDetails::create([
                'bill_id'=>$b_id,
                'stock_id'=>$request->stock[$i],
                'quantity'=>$request->quantity[$i],
              ]);
              $stock = Stock::findOrFail($request->stock[$i]);
              $stock->quantity -=$request->quantity[$i];
              $stock->save();

            }

      return response()->json(['new_bill'=>$bill],201);
}


public function search_customer(Request $request)
{
  $customer = Customer::where('name','like',"%$request->search%")
                      ->orWhere('phone','like',"%$request->search%")
                      ->with('bills')
                      ->limit(5)
                      ->get();
                  return response()->json(['customer'=>$customer],201);

}

  public function edit_bill(Request $request)
  {

      $id = $request->id;

      $current_bill = Bill::findOrFail($id);

      $current_bill->paid += $request->paid;

      $current_bill->remain = $request->remain;

      $current_bill->save();


      return response()->json(['current_bill'=>$current_bill],200);

  }


  public function show_bill_details(Request $request)
  {

      $bill_id = $request->bill_id;

      $bill_details = Bill::findOrFail($bill_id)->details;

      return response()->json(['data'=>$bill_details,$bill_id],200);


  }
  public function edit_bill_details(Request $request)
  {

    $detail_id = $request->detail_id;
    $bill_id = $request->bill_id;

    $quantiy = $request->quantity;


    $bill = Bill::findOrFail($bill_id);

    $detail = BillDetails::findOrFail($detail_id);

    if ($quantiy > $detail->quantity) {

      //edit details
      $q_diff = abs($quantiy - $detail->quantity) + $detail->quantity;
      $detail->quantity = $q_diff;

      $stock_id = $detail->stock_id;
        $stock = Stock::findOrFail($stock_id);

              if ($q_diff > $stock->quantity ) {
                return response()->json(['msg'=>'الكمية المعطاة اكبر من الموجودة في المخزن'],422);

              }

      $detail->save();


      //edit bill

      $stock = Stock::findOrFail($stock_id);


      $edit_total = $stock->price * $q_diff * $stock->size;

      $bill->total += $edit_total;

      $bill->remain = $bill->total - $bill->paid;
      $bill->save();

      //apply in stock
      $stock->quantity -= $q_diff;
      $stock->save();
    }

    elseif ($quantiy < $detail->quantity) {
      //edit details
      $q_diff = abs($quantiy - $detail->quantity) ;
      $detail->quantity -= $q_diff;
      $stock_id = $detail->stock_id;
      $stock = Stock::findOrFail($stock_id);

            if ($q_diff > $stock->quantity ) {
              return response()->json(['msg'=>'الكمية المعطاة اكبر من الموجودة في المخزن'],422);
            }

      $detail->save();


      //edit bill
      $stock = Stock::findOrFail($stock_id);

      $edit_total = -($stock->price * $q_diff * $stock->size);

      $bill->total += $edit_total;

      $bill->remain = $bill->total - $bill->paid;

      $bill->save();

      //apply in stock
      $stock->quantity += $q_diff;
      $stock->save();

    }
    return response()->json(['bill'=>$bill,'detail'=>$quantiy],200);
  }

  public function delete_bill_details(Request $request)
  {
        $detail_id = $request->id;
        $stock_id = $request->stock_id;
        $bill_id = $request->bill_id;

        //get it
        $current_detail = BillDetails::findOrFail($detail_id);


        //stock

        $stock = Stock::findOrFail($stock_id);

        //back quantity
        $stock->quantity += $current_detail->quantity;


        //back money
        $edit_total = $current_detail->quantity * $stock->price * $stock->size;


        $bill = Bill::findOrFail($bill_id);

        $bill->total -= $edit_total;

        //done
        $bill->save();

        $stock->save();

        $current_detail->delete();

        return response()->json(['msg'=>'deleed'],201);
  }

}
