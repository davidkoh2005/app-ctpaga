<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class HistoryCashesExport implements FromView, WithDrawings
{
    protected $histories, $startDate, $endDate;
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($histories, $startDate, $endDate) {
        $this->histories = $histories;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');

        $drawing->setDescription('Logo '.env('APP_NAME'));
        $drawing->setPath(public_path('/images/logo/logo.png'));
        $drawing->setWidth(240);

        $drawing->setCoordinates('A1');

        return $drawing;
    }


    public function view(): View
    {
        return view('report.historyCashesExcel', [
            'histories'      => $this->histories,
            'startDate'     => $this->startDate,
            'endDate'       => $this->endDate,
        ]);
    }
}
