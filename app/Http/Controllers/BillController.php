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


            /* LOOP
                   sotck_ids,
                    quantity,
            */
            for ($i=0; $i < count($request->stock); $i++) {

              //Bill details
              $bill = BillDetails::create([
                'bill_id'=>$bill->id,
                'stock_id'=>$request->stock[$i],
                'quantity'=>$request->quantity[$i],
              ]);

            }

      return response()->json(['new_bill'=>$bill],201);
}
public function search_customer(Request $request)
{
  $customer = Customer::where('name','like',"%$request->search%")
                      ->orWhere('phone','like',"%$request->search%")
                      ->with('bills')
                      ->get();


  return response()->json(['customer'=>$customer],201);

}

}
