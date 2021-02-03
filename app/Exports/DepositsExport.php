<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DepositsExport implements FromView, WithDrawings
{
    protected $deposits, $today;
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($deposits, $today) {
        $this->deposits = $deposits;
        $this->today = $today;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Loco Ctpaga');
        $drawing->setPath(public_path('/images/logo/logo.png'));
        $drawing->setWidth(140);
        $drawing->setCoordinates('E1');

        return $drawing;
    }


    public function view(): View
    {

        return view('report.depositsEXCEL', [
            'deposits'  => $this->deposits,
            'today'     => $this->today,
        ]);
    }
}