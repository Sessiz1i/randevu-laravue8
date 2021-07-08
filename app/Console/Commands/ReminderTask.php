<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Mail;
use SebastianBergmann\Diff\Exception;
use App\Models\WorkingHour;

class ReminderTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Reminder:Start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = date('Y-m-d');
        $nextDate = date('Y-m-d', strtotime('+1 Day', time()));
        $list = Appointment::whereIn('date', [$date, $nextDate])
            ->where('isActive', APPOINTMENT_SUCCESS)
            ->where('isSend', REMINDER_DEFAULT)
            ->get();
        foreach ($list as $k => $v)
        {

            if ($v['notification_type'] == NOTIFICATION_EMAIL)
            {
                $data =[
                    'name'=> $v['fullName'],
                    'email'=> $v['email'],
                    'date'=> $v['date'],
                    'time'=> WorkingHour::whereId($v['workingHour'])->value('hours'),
                    'code' => $v['code']
                ];
                try
                {
                    Mail::send('email', $data, function ($message) use ($data) {
                        $message->to($data['email'],$data['name'])->subject('Randevu Hatırlatma');
                        $message->from('omer.olgn@gmail.com');
                    });
                    Appointment::where('id', $v['id'])->update(['isSend' => REMINDER_SUCCESS]);
                }
                catch (Exception $err)
                {
                    Appointment::where('id', $v['id'])->update(['isSend' => REMINDER_FAILED]);
                }
            }

        }
    }
}
