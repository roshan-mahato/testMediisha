<?php

namespace App\Http\Controllers\superAdmin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\Setting;
use App\Models\Settle;
use App\Models\User;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use OneSignal;
use App\Http\Controllers\SuperAdmin\CustomController;
use App\Mail\SendMail;
use App\Models\NotificationTemplate;
use App\Models\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function calendar()
    {
        abort_if(Gate::denies('appointment_calendar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('superAdmin.appointment.calendar');
    }

    public function inCalendar()
    {
        (new CustomController)->cancel_max_order();
        if(auth()->user()->hasRole('super admin'))
        {
            $appointments = Appointment::with('user')->get();
            return response(['success' => true , 'data' => $appointments]);
        }
        if(auth()->user()->hasRole('doctor'))
        {
            $appointments = Appointment::with('user')->get();
            return response(['success' => true , 'data' => $appointments]);
        }
    }

    public function commission()
    {
        (new CustomController)->cancel_max_order();
        abort_if(Gate::denies('commission_details'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        $now = Carbon::today(env('timezone'));
        $appointments = array();
        for ($i = 0; $i < 7; $i++)
        {
            $appointment = Appointment::where('doctor_id',$doctor->id)->whereDate('created_at', $now)->get();
            $appointment['amount'] = $appointment->sum('amount');
            $appointment['admin_commission'] = $appointment->sum('admin_commission');
            $appointment['doctor_commission'] = $appointment->sum('doctor_commission');
            $now =  $now->subDay();
            $appointment['date'] = $now->toDateString();
            array_push($appointments,$appointment);
        }

        $currency = Setting::first()->currency_symbol;

        $past = Carbon::now(env('timezone'))->subDays(35);
        $now = Carbon::today(env('timezone'));
        $c = $now->diffInDays($past);
        $loop = $c / 10;
        $data = [];
        while ($now->greaterThan($past)) {
            $t = $past->copy();
            $t->addDay();
            $temp['start'] = $t->toDateString();
            $past->addDays(10);
            if ($past->greaterThan($now)) {
                $temp['end'] = $now->toDateString();
            } else {
                $temp['end'] = $past->toDateString();
            }
            array_push($data, $temp);
        }

        $settels = array();
        $orderIds = array();
        foreach ($data as $key)
        {
            $settle = Settle::where('doctor_id', $doctor->id)->where('created_at', '>=', $key['start'].' 00.00.00')->where('created_at', '<=', $key['end'].' 23.59.59')->get();
            $value['d_total_task'] = $settle->count();
            $value['admin_earning'] = $settle->sum('admin_amount');
            $value['doctor_earning'] = $settle->sum('doctor_amount');
            $value['d_total_amount'] = $value['admin_earning'] + $value['doctor_earning'];
            $remainingOnline = Settle::where([['doctor_id', $doctor->id], ['payment', 0],['doctor_status', 0]])->where('created_at', '>=', $key['start'].' 00.00.00')->where('created_at', '<=', $key['end'].' 23.59.59')->get();
            $remainingOffline = Settle::where([['doctor_id', $doctor->id], ['payment', 1],['doctor_status', 0]])->where('created_at', '>=', $key['start'].' 00.00.00')->where('created_at', '<=', $key['end'].' 23.59.59')->get();

            $online = $remainingOnline->sum('doctor_amount'); // admin e devana
            $offline = $remainingOffline->sum('admin_amount'); // admin e levana

            $value['duration'] = $key['start'] . ' - ' . $key['end'];
            $value['d_balance'] = $offline - $online; // + hoy to levana - devana
            array_push($settels,$value);
        }
        return view('superAdmin.appointment.commission',compact('doctor', 'appointments', 'currency','settels'));
    }

    public function show_settlement(Request $request)
    {
        $duration = explode(' - ',$request->duration);
        $currency = Setting::first()->currency_symbol;
        $settle = Settle::where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->get();
        foreach($settle as $s)
        {
            $s->date = $s->created_at->toDateString();
        }
        return response(['success' => true , 'data' => $settle , 'currency' => $currency]);
    }

    public function acceptAppointment($appointment_id)
    {
        Appointment::find($appointment_id)->update(['appointment_status' => 'approve']);
        $this->notificationChange($appointment_id,'Accept');
        return redirect()->back()->with('status',__('status change successfully...!!'));
    }

    public function cancelAppointment($appointment_id)
    {
        Appointment::find($appointment_id)->update(['appointment_status' => 'cancel']);
        $this->notificationChange($appointment_id,'Cancel');
        return redirect()->back()->with('status',__('status change successfully...!!'));
    }

    public function completeAppointment($appointment_id)
    {
        $appointment = Appointment::find($appointment_id);
        Appointment::find($appointment_id)->update(['appointment_status' => 'complete','payment_status' => 1]);
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        if($doctor->based_on == 'commission')
        {
            $settle = array();
            $settle['appointment_id'] = $appointment->id;
            $settle['doctor_id'] = $appointment->doctor_id;
            $settle['doctor_amount'] = $appointment->doctor_commission;
            $settle['admin_amount'] = $appointment->admin_commission;
            $settle['payment'] = $appointment->payment_type == 'COD' ? 0 : 1;
            $settle['doctor_status'] = 0;
            Settle::create($settle);
        }
        $this->notificationChange($appointment_id,'Complete');
        return redirect()->back()->with('status',__('status change successfully...!!'));
    }

    // change Appointment to user
    public function notificationChange($appointment_id,$status)
    {
        $appointment = Appointment::with('user')->find($appointment_id);
        $notification_template = NotificationTemplate::where('title','status change')->first();
        $msg_content = $notification_template->msg_content;
        $mail_content = $notification_template->mail_content;
        $detail['user_name'] = $appointment->user->name;
        $detail['appointment_id'] = $appointment->appointment_id;
        $detail['date'] = $status;
        $detail['status'] = $appointment->date;
        $detail['app_name'] = Setting::first()->business_name;
        $user_data = ["{{user_name}}","{{appointment_id}}","{{date}}","{{status}}","{{app_name}}"];
        $mail1 = str_replace($user_data, $detail, $mail_content);
        $message1 = str_replace($user_data, $detail, $msg_content);
        if(Setting::first()->patient_mail == 1){
            try {
                Mail::to(auth()->user()->email)->send(new SendMail($mail1,$notification_template->subject));
            } catch (\Throwable $th) {
                
            }
        }

        if(Setting::first()->patient_notification == 1){
            try {
                Config::set('onesignal.app_id', env('patient_app_id'));
                Config::set('onesignal.rest_api_key', env('patient_api_key'));
                Config::set('onesignal.user_auth_key', env('patient_auth_key'));
                OneSignal::sendNotificationToUser(
                    $message1,
                    $appointment->user->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    Setting::first()->business_name
                );
            } catch (\Throwable $th) {
            }
        }

        $user_notification = array();
        $user_notification['user_id'] = auth()->user()->id;
        $user_notification['doctor_id'] = $appointment->doctor_id;
        $user_notification['user_type'] = 'user';
        $user_notification['title'] = 'create appointment';
        $user_notification['message'] = $message1;
        Notification::create($user_notification);
        return true;
    }
    
    public function appointment()
    {
        (new CustomController)->cancel_max_order();
        abort_if(Gate::denies('appointment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $currency = Setting::first()->currency_symbol;
        if(auth()->user()->hasRole('super admin'))
        {
            $appointments = Appointment::with(['doctor','address'])->orderBy('id','DESC')->get();
        }
        else
        {
            $doctor = Doctor::where('user_id',auth()->user()->id)->first();
            $appointments = Appointment::with(['doctor','address'])->where('doctor_id',$doctor->id)->orderBy('id','DESC')->get();
            foreach ($appointments as $appointment)
            {
                if(Prescription::where('appointment_id',$appointment->id)->first())
                {
                    $appointment->prescription = '1';
                    $appointment->preData = Prescription::where('appointment_id',$appointment->id)->first();
                }
                else
                {
                    $appointment->prescription = '0';
                }
            }
        }
        return view('superAdmin.appointment.appointment',compact('appointments','currency'));
    }

    public function show_appointment($appointment_id)
    {
        (new CustomController)->cancel_max_order();
        $currency = Setting::first()->currency_symbol;
        $appointment = Appointment::with(['doctor','address'])->find($appointment_id);
        return response(['success' => true , 'data' => $appointment , 'currency' => $currency]);
    }

    public function prescription($appointment_id)
    {
        (new CustomController)->cancel_max_order();
        $appointment = Appointment::with(['doctor','user'])->find($appointment_id);
        $doctor = Doctor::with(['expertise','treatment','hospital','category'])->find($appointment->doctor_id);
        $medicines = Medicine::whereStatus('1')->get();
        return view('superAdmin.doctor.prescription',compact('appointment','doctor','medicines'));
    }

    public function all_medicine()
    {
        $medicines = Medicine::whereStatus('1')->get();
        return response(['success' => true , 'data' => $medicines]);
    }

    public function addPrescription(Request $request)
    {
        $data = $request->all();
        $medicine = array();
        for ($i = 0; $i < count($data['medicines']); $i++)
        {
            $temp['medicine'] = $data['medicines'][$i];
            $temp['days'] = $data['day'][$i];
            $temp['morning'] = isset($data['morning'.$i]) ? 1 : 0;
            $temp['afternoon'] = isset($data['afternoon'.$i]) ? 1 : 0;
            $temp['night'] = isset($data['night'.$i]) ? 1 : 0;
            array_push($medicine,$temp);
        }
        $pre['medicines'] = json_encode($medicine);
        $pre['appointment_id'] = $data['appointment_id'];
        $pre['doctor_id'] = Doctor::where('user_id',auth()->user()->id)->first()->id;
        $pre['user_id'] = $data['user_id'];
        $pres = Prescription::create($pre);
        $prescription = Prescription::with(['doctor','user'])->find($pres->id);
        $prescription->doctorUser = User::find($prescription->doctor['user_id']);

        $medicineName = $pres->medicines;
        $pdf = PDF::loadView('temp', compact('medicineName'));
        $path = public_path() . '/prescription/upload';
        $fileName =  uniqid() . '.' . 'pdf' ;
        $pdf->save($path . '/' . $fileName);
        $pres->pdf = $fileName;
        $pres->save();
        return redirect('/appointment');
    }
}
