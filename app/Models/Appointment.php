<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Appointment extends Model
{
    use HasFactory;
    protected $appends=['hour'];

    protected $guarded  = [];
    protected $fillable = [];

    public function getHourAttribute()
    {
        return WorkingHour::whereId($this->workingHour)->value('hours');
    }
    public function hours(){
        return $this->hasOne(WorkingHour::class,'id','workingHour');
    }

}
