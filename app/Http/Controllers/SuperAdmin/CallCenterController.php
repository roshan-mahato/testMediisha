<?php
namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SendMail;
use App\Models\Country;
use App\Models\Hospital;
use App\Models\CallCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\CallCenterWorkingHour;
use App\Http\Controllers\SuperAdmin\CustomController;

class CallCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $callcenters = CallCenter::all();
        foreach ($callcenters as $callcenter) {
            $callcenter->user = User::find($callcenter->user_id);
        }
        return view('superAdmin.callcenter.callcenter', compact('callcenters'));
        // return view('superAdmin.callcenter.callcenter');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $countries = Country::get();
        $hospitals = Hospital::whereStatus(1)->get();
        return view('superAdmin.callcenter.create_callcenter',compact('countries','hospitals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required|unique:callcenter',
            'email' => 'bail|required|email|unique:users',
            'dob' => 'bail|required',
            'gender' => 'bail|required',
            'phone' => 'bail|required|digits_between:6,12',
            'start_time' => 'bail|required',
            'end_time' => 'bail|required|after:start_time',
            'hospital_id' => 'bail|required',
            'experience' => 'bail|required|numeric',
        ]);
        $data = $request->all();
        // $password = mt_rand(100000,999999);
        $password = 123456;
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'verify' => 1,
            'phone' => $data['phone'],
            'phone_code' => $data['phone_code'],
            'image' => 'defaultUser.png'
        ],
        [
            'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
        ]);
        $message1 = 'Dear Call Center your password is : '.$password;
        try
        {
            Mail::to($user->email)->send(new SendMail($message1,'Call Center Password'));
        }
        catch (\Throwable $th)
        {

        }
        $user->assignRole('callcenter');
        $data['user_id'] = $user->id;
        $data['start_time'] = strtolower(Carbon::parse($data['start_time'])->format('h:i a'));
        $data['end_time'] = strtolower(Carbon::parse($data['end_time'])->format('h:i a'));
        if($request->hasFile('image'))
        {
            $data['image'] = (new CustomController)->imageUpload($request->image);
        }
        else
        {
            $data['image'] = 'defaultUser.png';
        }
        $education = array();
        for ($i=0; $i < count($data['degree']); $i++)
        {
            $temp['degree'] = $data['degree'][$i];
            $temp['college'] = $data['college'][$i];
            $temp['year'] = $data['year'][$i];
            array_push($education,$temp);
        }
        $data['education'] = json_encode($education);
        $certificate = array();
        for ($i=0; $i < count($data['certificate']); $i++)
        {
            $temp1['certificate'] = $data['certificate'][$i];
            $temp1['certificate_year'] = $data['certificate_year'][$i];
            array_push($certificate,$temp1);
        }
        $data['certificate'] = json_encode($certificate);
        $data['since'] = Carbon::now(env('timezone'))->format('Y-m-d , h:i A');
        $data['status'] = 1;
        $callcenter = CallCenter::create($data);
        $start_time = strtolower($callcenter->start_time);
        $end_time = strtolower($callcenter->end_time);
        $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $temp2['start_time'] = $start_time;
            $temp2['end_time'] = $end_time;
            array_push($master,$temp2);
            $work_time['callCenter_id'] = $callcenter->id;
            $work_time['period_list'] = json_encode($master);
            $work_time['day_index'] = $days[$i];
            $work_time['status'] = 1;
            CallCenterWorkingHour::create($work_time);
        }
        return redirect('callcenter')->withStatus(__('Call Center created successfully..!!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //abort_if(Gate::denies('doctor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $callcenter = CallCenter::find($id);
        $callcenter->user = User::find($callcenter->user_id);
        $countries = Country::get();
        $hospitals = Hospital::get();
        $callcenter['start_time'] = Carbon::parse($callcenter['start_time'])->format('H:i');
        $callcenter['end_time'] = Carbon::parse($callcenter['end_time'])->format('H:i');
        return view('superAdmin.callcenter.edit_callcenter',compact('callcenter','countries','hospitals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'bail|required|unique:callcenter,name,' . $id . ',id',
            'treatment_id' => 'bail|required',
            'category_id' => 'bail|required',
            'dob' => 'bail|required',
            'gender' => 'bail|required',
            'phone' => 'bail|required|digits_between:6,12',
            'start_time' => 'bail|required',
            'end_time' => 'bail|required|after:start_time',
            'hospital_id' => 'bail|required',
            'experience' => 'bail|required|numeric',
            'image' => 'bail|max:1000',
        ],
        [
            'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
        ]);
        $callcenter = CallCenter::find($id);
        $data = $request->all();

        $data['start_time'] = Carbon::parse($data['start_time'])->format('h:i A');
        $data['end_time'] = Carbon::parse($data['end_time'])->format('h:i A');
        if($request->hasFile('image'))
        {
            (new CustomController)->deleteFile($callcenter->image);
            $data['image'] = (new CustomController)->imageUpload($request->image);
        }
        $education = array();
        for ($i=0; $i < count($data['degree']); $i++)
        {
            $temp['degree'] = $data['degree'][$i];
            $temp['college'] = $data['college'][$i];
            $temp['year'] = $data['year'][$i];
            array_push($education,$temp);
        }
        $data['education'] = json_encode($education);
        $certificate = array();
        for ($i=0; $i < count($data['certificate']); $i++)
        {
            $temp1['certificate'] = $data['certificate'][$i];
            $temp1['certificate_year'] = $data['certificate_year'][$i];
            array_push($certificate,$temp1);
        }
        $data['certificate'] = json_encode($certificate);
        $data['is_filled'] = 1;
        $data['custom_timeslot'] = $request->custom_time == '' ? null : $request->custom_time;
        $callcenter->update($data);
        return redirect('callcenter')->withStatus(__('Call Center updated successfully..!!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //abort_if(Gate::denies('doctor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = CallCenter::find($id);
        $user = User::find($id->user_id);
        $user->removeRole('callcenter');
        $user->delete();
        (new CustomController)->deleteFile($id->image);
        $id->delete();
        return response(['success' => true]);
    }
}
