<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\BankData;
use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Models\BankDataCategory;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BankDataExport;

class DashboardController extends Controller
{
    private function convertToShortForm($number)
    {
        if ($number >= 1000 && $number < 1000000) {
            return round($number / 1000, 1) . 'rb';
        } elseif ($number >= 1000000 && $number < 1000000000) {
            return round($number / 1000000, 1) . 'jt';
        } elseif ($number >= 1000000000) {
            return round($number / 1000000000, 1) . 'M';
        } else {
            return $number;
        }
    }

    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Dashboard',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        return view('admin.dashboard', compact('config'));
    }
}