<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SettingsBank;

class SettingsBankController extends Controller
{
    public function showData(Request $request)
    {
        $banks = SettingsBank::where('bank', $request->selectBank)->where('type', $request->type)->first();
        return response()->json(['statusCode' => 201,'data' => $banks]);
    }

}
