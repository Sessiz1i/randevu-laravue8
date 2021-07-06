<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\WorkingHour;
use Illuminate\Support\Facades\Date;

class indexController extends Controller
{
    public function confirmation(Request $request)
    {
        $data = $request->post();
        Appointment::where('id',$data['id'])->update(['isActive' => $data['type']]);
        return response()->json(['status' => true]);
    }

    public function all()
    {
        $returnArray=[];
        // Waiting List
        $returnArray['waiting'] = Appointment::where('isActive','=',0)->orderBy('date','desc')->orderBy('workingHour','desc')->paginate(3);
        // Today List
        $returnArray['today'] = Appointment::where('isActive','=',1)->where('date','=',date('Y-m-d'))->orderBy('workingHour','asc')->paginate(3);
        // All List
        $returnArray['allList'] = Appointment::where('isActive','=',1)->where('date','>',date('Y-m-d'))->orderBy('date','asc')->orderBy('workingHour','asc')->paginate(3);
        // Last List
        $returnArray['last'] = Appointment::where('date','<',date('Y-m-d'))->orderBy('date','asc')->paginate(3);
        // Cancel List
        $returnArray['cancel'] = Appointment::where('isActive','=',2)->orderBy('date','asc')->paginate(3);
        return response()->json($returnArray);
    }
}
