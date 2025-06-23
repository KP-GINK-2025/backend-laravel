<?php

namespace App\Exports;

use App\Models\BankData;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BankDataExport implements FromView
{
    protected $startDate;
    protected $endDate;
    protected $instansi;

    public function __construct($instansi, $startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->instansi = $instansi;
    }

    public function view(): View
    {
        if (!empty($this->instansi)) {
            $data = BankData::whereHas('categories', function ($query) {
                $query->where('instansi_id', $this->instansi);
            })->with([
                        'categories' => function ($query) {
                            $query->where('instansi_id', $this->instansi)->with('instansi');
                        }
                    ])->whereBetween('created_at', [$this->startDate, $this->endDate])->get();
        } else {
            $data = BankData::with('categories.instansi')->whereBetween('created_at', [$this->startDate, $this->endDate])->get();
        }
        return view('exports.bank_data', ['bank_data' => $data]);
    }
}