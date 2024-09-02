<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function createOrder()
    {
        return view('pages.dashboard.sale-page');
    }
    public function invoiceList()
    {
        return view('pages.dashboard.invoice-page');
    }

    public function allInvoice(Request $request)
    {
        $user_id = $request->header('id');
        return Invoice::where('user_id', $user_id)->with('customer')->get();

    }

    public function invoiceCreate(Request $request)
    {

        $request->validate([
            'total' => 'required|numeric',
            'discount' => 'required|numeric',
            'vat' => 'required|numeric',
            'payable' => 'required|numeric',
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty' => 'required|numeric',
            'products.*.sale_price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');
            $customer_id = $request->input('customer_id');

            $invoice = Invoice::create([
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'user_id' => $user_id,
                'customer_id' => $customer_id,
            ]);

            $invoiceID = $invoice->id;
            $products = $request->input('products');

            foreach ($products as $EachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'user_id' => $user_id,
                    'product_id' => $EachProduct['product_id'],
                    'qty' => $EachProduct['qty'],
                    'sale_price' => $EachProduct['sale_price'],
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Request Successfull'], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 400);
        }
    }

    public function invoiceInfo(Request $request)
    {
        $user_id = $request->header('id');
        $customerDetails = Customer::where('user_id', $user_id)->where('id', $request->input('cus_id'))->first();
        $invoiceTotal = Invoice::where('user_id', $user_id)->where('id', $request->input('inv_id'))->first();
        $invoiceProduct = InvoiceProduct::where('invoice_id', $request->input('inv_id'))
            ->where('user_id', $user_id)->with('product')
            ->get();
        return array(
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct,
        );

    }

    public function invoiceDelete(Request $request)
    {

        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            InvoiceProduct::where('invoice_id', $request->input('inv_id'))
                ->where('user_id', $user_id)
                ->delete();
            Invoice::where('id', $request->input('inv_id'))->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Request Successfull'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 404);
        }

    }
}
