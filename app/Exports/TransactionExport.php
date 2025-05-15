<?php

namespace App\Exports;

use Modules\Booking\Models\Booking;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Space\Models\Space;
use Modules\User\Models\Wallet\Transaction;

class TransactionExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }
    public function headings(): array
    {
        return [
            '#ID',
            'Date',
            'Type',
            'Amount',
            'Status', 
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->created_at,
            $transaction->type,
            $transaction->amount,
            $transaction->status_name
        ];
    }

    public function query()
    {
        return Transaction::whereIn('id', $this->selectedRows);
    }

}