<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getPrice($price)
    {
        $price = str_replace("$ ","",$price);
        $price = str_replace("Bs ","",$price);
        $price = str_replace(".","",$price);
        $price = str_replace(",",".",$price);
        
        return $price;
    }

    public function getPriceSales($price)
    {
        $price = str_replace("$ ","",$price);
        $price = str_replace("Bs ","",$price);
        $price = str_replace(".",",",$price);
        
        return $price;
    }

    public function getPriceAmount($price)
    {
        $price = str_replace("$ ","",$price);
        $price = str_replace("Bs ","",$price);
        $price = str_replace(",",".",$price);
        
        return $price;
    }

    public function getPriceShipping($price)
    {
        $price = str_replace("$ ","",$price);
        $price = str_replace("Bs ","",$price);
        $price = str_replace(",",".",$price);
        
        return "$price";
    }
}
