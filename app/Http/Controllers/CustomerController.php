<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customer()
    {
        return view('pages.dashboard.customer-page');
    }

    public function customerList(Request $request)
    {
        $customers = Customer::where('user_id', $request->header('id'))->get();
        return response($customers, 200);
    }

    public function customerCreate(CustomerRequest $request)
    {
        $request->validated();
        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_id' => $request->header('id'),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Request Successful'], 201);
    }
    public function customerInfo(Request $request)
    {
        $customer = Customer::find($request->id);

        if ($customer) {
            return response($customer, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 404);
        }
    }

    public function customerUpdate(CustomerRequest $request)
    {
        try {
            $request->validated();
            $customer = Customer::find($request->id);
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            return response()->json(['status' => 'success', 'message' => 'Request Success'], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 400);
        }
    }

    public function customerDelete(Request $request)
    {
        $customer = Customer::find($request->id);
        if ($customer) {
            return $customer->delete();

        } else {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 404);
        }
    }
}
