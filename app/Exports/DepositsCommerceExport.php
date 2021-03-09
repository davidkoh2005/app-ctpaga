<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DepositsCommerceExport implements FromView, WithDrawings
{
    protected $historyDeposits, $today, $startDate, $endDate, $commerceData, $pictureUser;
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($historyDeposits, $today, $startDate, $endDate, $commerceData, $pictureUser) {
        $this->historyDeposits = $historyDeposits;
        $this->today = $today;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->commerceData = $commerceData;
        $this->pictureUser = $pictureUser;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Loco Comercio');
        $drawing->setPath(public_path($this->pictureUser->url));
        $drawing->setWidth(100);
        $drawing->setCoordinates('E1');

        return $drawing;
    }


    public function view(): View
    {

        return view('report.depositsCommerceEXCEL', [
            'historyDeposits'      => $this->historyDeposits,
            'today'         => $this->today,
            'startDate'     => $this->startDate,
            'endDate'       => $this->endDate,
            'commerceData'  => $this->commerceData,
        ]);
    }
}
