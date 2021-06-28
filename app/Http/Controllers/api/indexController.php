<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingHour;
use App\Models\Appointment;

class indexController extends Controller
{
    public function getworkingHours($date ='')
    {
        $date = ($date ==='') ? date('Y-m-d'): $date;
        $returnArray = [];
        $hours = WorkingHour::all();
        foreach ($hours as $k => $v)
        {
            $control = Appointment::where('date', $date)->where('workingHour', $v['id'])->count();
            $v['isActive'] = ($control === 0) ? true : false;
           $returnArray[] = $v;
        }
        return response()->json($returnArray);
    }
}
