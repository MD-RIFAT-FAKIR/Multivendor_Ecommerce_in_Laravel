<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use DateTime;

class ReportController extends Controller
{
    //
    public function ReportView() {
        return view('backend.reports.report_view');
    }

    //report search by date
    public function ReporSearchByDate(Request $request) {
        $data = new DateTime($request->date);
        $dateFormat = $data->format('d F Y');

        $orders = Order::where('order_date', '=', $dateFormat)->latest()->get();

        return view('backend.reports.report_by_date', compact('orders', 'dateFormat'));
    }

    public function ReporSearchByMonth(Request $request) {
        $month = $request->month;
        $year_name = $request->year_name;

        $orders = Order::where('order_month', '=', $month)->where('order_year', '=', $year_name)->latest()->get();

        return view('backend.reports.report_by_month', compact('orders', 'month', 'year_name'));
    }

    public function ReporSearchByYear(Request $request) {
        $year = $request->year;

        $orders = Order::where('order_year', '=', $year)->latest()->get();

         return view('backend.reports.report_by_year', compact('orders', 'year'));

    }
}
