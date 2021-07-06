<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingHour;

class workingHourController extends Controller
{
    public function workingHourStore(Request $request)
    {
        $data = $request->post();
        WorkingHour::query()->delete();
        foreach ($data as $day => $times)
        {
            foreach ($times as $time)
            {
                WorkingHour::create([
                    'day' => $day,
                    'hours' => $time,
                ]);
            }
        }
    }

    public function getWorkingList()
    {
        $returnArray = [];
        $data = WorkingHour::orderBy('hours','asc')->get();
        foreach ($data as $value)
        {
            $returnArray[$value['day']][] = $value['hours'];
        }
        return response()->json($returnArray);
    }
}
