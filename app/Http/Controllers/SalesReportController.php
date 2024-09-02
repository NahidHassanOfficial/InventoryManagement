<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request as HttpRequest;

class SalesReportController extends Controller
{

    public function salesReport()
    {
        return view('pages.dashboard.report-page');
    }

    public function generateReport(HttpRequest $request)
    {
        try {
            $user = $request->header('id');
            if ($user) {
                $fromDate = date('Y-m-d', strtotime($request->fromDate));
                $toDate = date('Y-m-d', strtotime($request->toDate));

                $total = Invoice::where('user_id', $user)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)->sum('total');
                $vat = Invoice::where('user_id', $user)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)->sum('vat');
                $payable = Invoice::where('user_id', $user)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)->sum('payable');
                $discount = Invoice::where('user_id', $user)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)->sum('discount');

                $list = Invoice::where('user_id', $user)
                    ->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate)
                    ->with('customer')->get();

                $data = [
                    'payable' => $payable,
                    'discount' => $discount,
                    'total' => $total,
                    'vat' => $vat,
                    'list' => $list,
                    'FormDate' => $request->fromDate,
                    'ToDate' => $request->toDate,
                ];

                $pdf = Pdf::loadview('components.report-page.SalesReport', $data);
                return $pdf->download('sales-report.pdf');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 500);
        }
    }
}
