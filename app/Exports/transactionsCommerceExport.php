<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TransactionsCommerceExport implements FromView, WithDrawings
{
    protected $transactions, $today, $startDate, $endDate, $commerce, $pictureUser;
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($transactions, $today, $commerce, $pictureUser, $startDate, $endDate) {
        $this->transactions = $transactions;
        $this->today = $today;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->commerce = $commerce;
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
            $drawing->setPath(public_path("images/perfilUser.png"));
        $drawing->setWidth(100);
        $drawing->setCoordinates('A1');

        return $drawing;
    }


    public function view(): View
    {

        return view('report.transactionsCommerceEXCEL', [
            'transactions'      => $this->transactions,
            'today'         => $this->today,
            'startDate'     => $this->startDate,
            'endDate'       => $this->endDate,
            'commerce'  => $this->commerce,
        ]);
    }
}
