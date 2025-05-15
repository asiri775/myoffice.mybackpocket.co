<?php

namespace App\Exports;

use Modules\Booking\Models\Booking;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Space\Models\Space;

class BookingExport implements FromQuery, WithMapping, WithHeadings
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
            'Title',
            'City',
            'Start Date',
            'End Date',
            'Total',
            'Book Status',
            'Transaction Status' 
        ];
    }

    public function map($booking): array
    {

        $space = Space::where('id', $booking->object_id)->first();
        $city=($space->location_id!='' AND $space->location->translateOrOrigin(app()->getLocale())->name !='') ? $space->location->translateOrOrigin(app()->getLocale())->name : '';
        $title=($space->location_id!='' AND $space->location->translateOrOrigin(app()->getLocale())->name !='') ? $space->location->translateOrOrigin(app()->getLocale())->title : '';
        return [
            $booking->id,
            $title,
            $city,
            date('m-d-Y',strtotime($booking->start_date)),
            date('m-d-Y',strtotime($booking->end_date)),
            '$' . $booking->total,
            str_replace('_', ' ', strtoupper($booking->status)),
            $booking->is_paid == 1 ? 'PAID' : 'UNPAID'
        ];
    }

    public function query()
    {
        return Booking::whereIn('id', $this->selectedRows);
    }

}
