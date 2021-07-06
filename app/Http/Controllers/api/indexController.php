<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingHour;
use App\Models\Appointment;

class indexController extends Controller
{
    public function getWorkingHours($date = '')
    {
        $today = date('Y-m-d');
        $date = ($date === '') ? date('Y-m-d') : $date;
        $day = date('l', strtotime($date));
        $returnArray = [];
        $hours = WorkingHour::where('day', $day)->orderBy('hours', 'asc')->get();
        foreach ($hours as $hour)
        {
            $control = Appointment::where('date', $date)->where('workingHour', $hour['hours'])->where('isActive', '<', 2)->count();

            $exp = explode(' - ', $hour['hours']);
            $nowTime = date('H:i');
            if ($control === 0)
            {
                if ($date == $today)
                {
                    if ($exp[0] > $nowTime)
                    {
                        $hour['isActive'] = true;
                    }
                    else
                    {
                        $hour['isActive'] = false;
                    }
                }
                else
                {
                    $hour['isActive'] = true;
                }

            }


            $returnArray[] = $hour;
        }
        return response()->json($returnArray);
    }

    public function appointmentStore(Request $request)
    {
        $returnArray = [];
        $returnArray['status'] = false;
        $data = $request->post();
        $control = Appointment::where('date', $data['date'])->where('workingHour', $data['workingHour'])->where('isActive', '<', 2)->count();
        if ($control > 0)
        {
            $returnArray ['message'] = "Bu Randevu tarihi doludur.";
            return response()->json($returnArray);
        }
        else
        {
            $create = Appointment::create($data);
            $create->save();
            if ($create)
            {
                $returnArray['status'] = true;
                $returnArray['message'] = "Randevu kaydınız tamamlanmıştır";
            }
            else
            {
                $returnArray['message'] = "Randevu Kayıdı başarısız Lütfen iletişime geçiniz.";
            }
        }
        return response()->json($returnArray);
    }
}
