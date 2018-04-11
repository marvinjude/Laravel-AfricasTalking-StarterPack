<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronEndPointController extends Controller
{
    public function toUsersThatMatchRule(){
        Artisan::call('message:sms');
    }

    //Hit This Endpoint When You just Wanna Dispatch Messages To All Users Regardless Of Reg date
    public function toAllUsers()
    {
        Artisan::call('message:sms-all');
    }
}
