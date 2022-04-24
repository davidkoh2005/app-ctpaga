<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ratesExport implements FromView, WithDrawings
{
    protected $rates, $startDate, $endDate, $commerceData, $pictureUser;
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($rates, $commerceData, $pictureUser, $startDate, $endDate) {
        $this->rates = $rates;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->commerceData = $commerceData;
        $this->pictureUser = $pictureUser;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');

        if(Auth::guard('admin')->check()){
            $drawing->setDescription('Logo '.env('APP_NAME'));
            $drawing->setPath(public_path('/images/logo/logo.png'));
            $drawing->setWidth(240);
        }else{
            $drawing->setDescription('Loco Comercio');
            
            if($this->pictureUser)
                $drawing->setPath(public_path($this->pictureUser->url));
            else
                $drawing->setPath(public_path("images/perfilUser.png"));
            
            $drawing->setWidth(100);
        }

        $drawing->setCoordinates('A1');

        return $drawing;
    }


    public function view(): View
    {
        if(Auth::guard('admin')->check()){
            return view('report.ratesEXCEL', [
                'rates'      => $this->rates,
                'startDate'     => $this->startDate,
                'endDate'       => $this->endDate,
            ]);
        }else{
            return view('report.ratesEXCEL', [
                'rates'      => $this->rates,
                'startDate'     => $this->startDate,
                'endDate'       => $this->endDate,
                'commerceData'  => $this->commerceData,
            ]);
        }
    }
}
