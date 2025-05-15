<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Space\Models\Space;

class BookingTransactionHistoryExport implements FromView
{
    use Exportable;
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.booking-transaction-history', [
            'data' => $this->data
        ]);
    }

}
