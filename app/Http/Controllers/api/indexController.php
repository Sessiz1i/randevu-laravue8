<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingHour;
use App\Models\Appointment;
use App\Models\AppointmentNote;

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

           if ($control === 0 && $date === $today) {
               $exp[0] > $nowTime ? $hour['isActive'] = true : $hour['isActive'] = false;
           }else{
               ($control === 0 && $date !== $today) ? $hour['isActive'] = true : $hour['isActive'] = false;
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
            $data['code'] = substr(bcrypt(uniqid()), 7, 10);
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

    public function appointmentNoteStore(Request $request)
    {
        if ($request['text'])
        {
            AppointmentNote::create($request->post());
        }
        $notes = AppointmentNote::where('appointmentId', $request['appointmentId'])->get();
        return response()->json($notes);
    }

    public function appointmentDetail(Request $request)
    {
        $retunArray = Appointment::where('code', $request->code)->first();

        if (!$retunArray)
        {
            $retunArray['status'] = false;
            $retunArray['message'] = "Girmiş olduğunuz koda ait randevu bulunmamaktadır.";
        }else{
            $retunArray['status'] = true;
            $retunArray['note'] = AppointmentNote::where('appointmentId',$retunArray->id)->orderBy('updated_at','desc')->get();
        }
        return response()->json($retunArray);
    }

}

