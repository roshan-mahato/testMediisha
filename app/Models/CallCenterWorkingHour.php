<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallCenterWorkingHour extends Model
{
    use HasFactory;

    protected $table = 'call_working_hour';

    protected $fillable = ['callCenter_id','day_index','period_list','status'];
}
