<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TransactionsExport implements FromView, WithDrawings
{
    protected $transactions, $today;
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($transactions, $today, $idCommerce, $companyName) {
        $this->transactions = $transactions;
        $this->today = $today;
        $this->idCommerce = $idCommerce;
        $this->companyName = $companyName;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Loco CTpaga');
        $drawing->setPath(public_path('/images/logo/logo.png'));
        $drawing->setWidth(240);
        $drawing->setCoordinates('A1');

        return $drawing;
    }


    public function view(): View
    {

        return view('report.transactionsEXCEL', [
            'transactions'      => $this->transactions,
            'today'         => $this->today,
            'idCommerce'    => $this->idCommerce,
            'companyName'   => $this->companyName,
        ]);
    }
}
