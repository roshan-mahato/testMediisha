<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\Expertise;
use App\Models\Lab;
use App\Models\LabWorkHours;
use App\Models\NotificationTemplate;
use App\Models\Report;
use App\Models\Setting;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class CustomController extends Controller
{
    public function imageUpload($image)
    {
        $file = $image;
        $fileName = uniqid() . '.' .$image->getClientOriginalExtension();
        $path = public_path() . '/images/upload';
        $file->move($path, $fileName);
        return $fileName;
    }

    public function deleteFile($file_name)
    {
        if($file_name != 'prod_default.png' && $file_name != 'defaultUser.png')
        {
            if(File::exists(public_path('images/upload/'.$file_name))){
                File::delete(public_path('images/upload/'.$file_name));
            }
            return true;
        }
    }

    public function display_category($id)
    {
        $categories = Category::where('treatment_id',$id)->get();
        return response(['success' => true , 'data' => $categories]);
    }

    public function display_expertise($id)
    {
        $expertises = Expertise::where('category_id',$id)->get();
        return response(['success' => true , 'data' => $expertises]);
    }

    public function updateENV($data)
    {
        $envFile = app()->environmentFilePath();
        if ($envFile)
        {
            $str = file_get_contents($envFile);
            if (count($data) > 0) {
                foreach ($data as $envKey => $envValue) {
                    $str .= "\n"; // In case the searched variable is in the last line without \n
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    // If key does not exist, add it
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)) {
            }
            return true;
        }
    }

    public function sendOtp($user)
    {
        $verification = Setting::first()->verification;
        if($verification == 1)
        {
            $otp = mt_rand(1000,9999);
            $user->update(['otp' => $otp]);
            $mail_notification = Setting::first()->using_mail;
            $msg_notification = Setting::first()->using_msg;
            $mail_content = NotificationTemplate::where('title','verification')->first()->mail_content;
            $msg_content = NotificationTemplate::where('title','verification')->first()->msg_content;
            $subject = NotificationTemplate::where('title','verification')->first()->subject;
            $detail['user_name'] = $user->name;
            $detail['otp'] = $otp;
            $detail['app_name'] = Setting::first()->business_name;
            $data = ["{{user_name}}","{{otp}}","{{app_name}}"];
            if($mail_notification == 1)
            {
                $message1 = str_replace($data, $detail, $mail_content);
                try {
                    Mail::to($user->email)->send(new SendMail($message1,$subject));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            if($msg_notification == 1)
            {
                $message1 = str_replace($data, $detail, $msg_content);
                $sid = Setting::first()->twilio_acc_id;
                $token = Setting::first()->twilio_auth_token;
                try {
                    $phone = $user->phone_code . $user->phone;
                        $message1 = str_replace($data, $detail, $msg_content);
                        $client = new Client($sid, $token);
                        $client->messages->create(
                            $phone,
                            array(
                                'from' => Setting::first()->twilio_phone_no,
                                'body' => $message1
                            )
                        );
                } catch (\Throwable $th) {

                }
            }
        }
    }

    public function timeSlot($doctor_id,$date)
    {
        $doctor = Doctor::find($doctor_id);
        $workingHours = WorkingHour::where('doctor_id',$doctor->id)->get();
        $master = [];
        $timeslot = $doctor->timeslot == 'other' ? $doctor->custom_timeslot : $doctor->timeslot;
        $dayname = Carbon::parse($date)->format('l');
        foreach ($workingHours as $hours)
        {
            if($hours->day_index == $dayname)
            {
                if($hours->status == 1)
                {
                    foreach (json_decode($hours->period_list) as $value)
                    {
                        $start_time = new Carbon($date . ' ' . $value->start_time);
                        if ($date == Carbon::now(env('timezone'))->format('Y-m-d')) {
                            $t = Carbon::now(env('timezone'));
                            $minutes = date('i', strtotime($t));
                            if ($minutes <= 30) {
                                $add = 30 - $minutes;
                            } else {
                                $add = 60 - $minutes;
                            }
                            $add += 60;
                            $d = $t->addMinutes($add)->format('h:i a');
                            $start_time = new Carbon($date . ' ' . $d);
                        }
                        $end_time = new Carbon($date . ' ' . $value->end_time);
                        $diff_in_minutes = $start_time->diffInMinutes($end_time);
                        for ($i = 0; $i <= $diff_in_minutes; $i += intval($timeslot))
                        {
                            if ($start_time >= $end_time)
                            {
                                break;
                            }
                            else
                            {
                                $temp['start_time'] = $start_time->format('h:i a');
                                $temp['end_time'] = $start_time->addMinutes($timeslot)->format('h:i a');
                                $time = strval($temp['start_time']);
                                $appointment = Appointment::where([['doctor_id', $doctor->id], ['time', $time], ['date', $date]])->first();
                                if ($appointment)
                                {
                                    //
                                } else {
                                    array_push($master, $temp);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $master;
    }

    public function LabtimeSlot($lab_id,$date)
    {
        $lab = Lab::find($lab_id);
        $workingHours = LabWorkHours::where('lab_id',$lab->id)->get();
        $master = [];
        $timeslot = 15;
        $dayname = Carbon::parse($date)->format('l');
        foreach ($workingHours as $hours)
        {
            if($hours->day_index == $dayname)
            {
                if($hours->status == 1)
                {
                    foreach (json_decode($hours->period_list) as $value)
                    {
                        $start_time = new Carbon($date . ' ' . $value->start_time);
                        if ($date == Carbon::now(env('timezone'))->format('Y-m-d')) {
                            $t = Carbon::now(env('timezone'));
                            $minutes = date('i', strtotime($t));
                            if ($minutes <= 30) {
                                $add = 30 - $minutes;
                            } else {
                                $add = 60 - $minutes;
                            }
                            $add += 60;
                            $d = $t->addMinutes($add)->format('h:i a');
                            $start_time = new Carbon($date . ' ' . $d);
                        }
                        $end_time = new Carbon($date . ' ' . $value->end_time);
                        $diff_in_minutes = $start_time->diffInMinutes($end_time);
                        for ($i = 0; $i <= $diff_in_minutes; $i += intval($timeslot))
                        {
                            if ($start_time >= $end_time)
                            {
                                break;
                            }
                            else
                            {
                                $temp['start_time'] = $start_time->format('h:i a');
                                $temp['end_time'] = $start_time->addMinutes($timeslot)->format('h:i a');
                                $time = strval($temp['start_time']);
                                $appointment = Report::where([['lab_id', $lab->id], ['time', $time], ['date', $date]])->first();
                                if ($appointment)
                                {
                                    // 
                                } else {
                                    array_push($master, $temp);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $master;
    }

    public function cancel_max_order()
    {
        $cancel_time = Setting::first()->auto_cancel;
        $dt = Carbon::now(env('timezone'));
        $formatted = $dt->subMinute($cancel_time);
        $cancel_orders = Appointment::where([['created_at', '<=', $formatted],['appointment_status','pending']])->get();
        foreach ($cancel_orders as $cancel_order)
        {
            $cancel_order->appointment_status = 'cancel';
            $cancel_order->save();
        }
        return true;
    }
}
