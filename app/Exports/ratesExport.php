<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ratesExport implements FromView, WithDrawings
{
    protected $rates, $today, $startDate, $endDate, $commerceData, $pictureUser;
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($rates, $today, $commerceData, $pictureUser, $startDate, $endDate) {
        $this->rates = $rates;
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
        if($this->pictureUser)
            $drawing->setPath(public_path($this->pictureUser->url));
        else
            $drawing->setPath(public_path("images/perfil.png"));
        $drawing->setWidth(100);
        $drawing->setCoordinates('E1');

        return $drawing;
    }


    public function view(): View
    {

        return view('report.ratesEXCEL', [
            'rates'      => $this->rates,
            'today'         => $this->today,
            'startDate'     => $this->startDate,
            'endDate'       => $this->endDate,
            'commerceData'  => $this->commerceData,
        ]);
    }
}
