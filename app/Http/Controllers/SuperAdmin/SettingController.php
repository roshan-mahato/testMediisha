<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Timezone;    
use Illuminate\Http\Request;
use LicenseBoxExternalAPI;
use App;

class SettingController extends Controller
{
    public function setting()
    {
        $setting = Setting::first();
        $timezones = Timezone::get();
        $currencies = Currency::get();
        $languages = Language::whereStatus(1)->get();
        return view('superAdmin.setting.setting',compact('setting','timezones','currencies','languages'));
    }

    public function update_general_setting(Request $request)
    {
        $request->validate([
            'email' => 'bail|email',
            'contact' => 'bail|digits_between:6,12',
            'company_white_logo' => 'bail|max:1000',
            'company_logo' => 'bail|max:1000',
            'company_favicon' => 'bail|max:1000',
        ],
        [
            'company_white_logo.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
        ],
        [
            'company_logo.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
        ],
        [
            'company_favicon.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
        ],
        );
        $setting = Setting::first();
        $data = $request->all();
        $data['currency_symbol'] = Currency::where('code',$data['currency_code'])->first()->symbol;
        if($request->hasFile('company_white_logo'))
        {
            (new CustomController)->deleteFile($setting->company_white_logo);
            $data['company_white_logo'] = (new CustomController)->imageUpload($request->company_white_logo);
        }
        if($request->hasFile('company_logo'))
        {
            (new CustomController)->deleteFile($setting->company_logo);
            $data['company_logo'] = (new CustomController)->imageUpload($request->company_logo);
        }
        if($request->hasFile('company_favicon'))
        {
            (new CustomController)->deleteFile($setting->company_favicon);
            $data['company_favicon'] = (new CustomController)->imageUpload($request->company_favicon);
        }
        $data['cancel_reason'] = json_encode($data['cancel_reason']);
        $setting->update($data);
        $timezone['timezone'] = $data['timezone'];
        $this->changeLanguage();
        (new CustomController)->updateENV($timezone);
        return redirect()->back()->withStatus(__('General Setting updated successfully..!!'));
    }

    public function changeLanguage()
    {
        $language = Setting::first()->language;
        App::setLocale($language);
        session()->put('locale', $language);
        $direction = Language::where('name',$language)->first()->direction;
        session()->put('direction', $direction);
        return true;
    }

    public function update_payment_setting(Request $request)
    {
        $request->validate([
            'stripe_public_key' => 'bail|required_if:stripe,1',
            'stripe_secret_key' => 'bail|required_if:stripe,1',
            'paypal_producation_key' => 'bail|required_if:paypal,1',
            'paypal_sandbox_key' => 'bail|required_if:paypal,1',
            'razor_key' => 'bail|required_if:razor,1',
            'paystack_public_key' => 'bail|required_if:paystack,1',
            'flutterwave_key' => 'bail|required_if:flutterwave,1'
        ]);
        $data = $request->all();
        $data['stripe'] = $request->has('stripe') ? 1 : 0;
        $data['cod'] = $request->has('cod') ? 1 : 0;
        $data['paypal'] = $request->has('paypal') ? 1 : 0;
        $data['razor'] = $request->has('razor') ? 1 : 0;
        $data['flutterwave'] = $request->has('flutterwave') ? 1 : 0;
        $data['paystack'] = $request->has('paystack') ? 1 : 0;
        $id = Setting::first();
        $id->update($data);
        return redirect()->back()->withStatus(__('Payment Setting updated successfully..!!'));
    }

    public function update_verification_setting(Request $request)
    {
        $request->validate([
            'twilio_auth_token' => 'bail|required_if:using_msg,1',
            'twilio_acc_id' => 'bail|required_if:using_msg,1',
            'twilio_phone_no' => 'bail|required_if:using_msg,1',
            'mail_mailer' => 'bail|required',
            'mail_host' => 'bail|required',
            'mail_port' => 'bail|required',
            'mail_username' => 'bail|required',
            'mail_password' => 'bail|required',
            'mail_encryption' => 'bail|required',
            'mail_from_address' => 'bail|required',
            'mail_from_name' => 'bail|required',
        ]);
        $data = $request->all();
        if(isset($data['verification']))
        {
            if(isset($data['using_mail']) || isset($data['using_msg']))
            {
                $data['verification'] = 1;

                if(isset($data['using_mail']))
                    $data['using_mail']  = 1;
                else
                    $data['using_mail']  = 0;

                if(isset($data['using_msg']))
                    $data['using_msg']  = 1;
                else
                    $data['using_msg']  = 0;
            }
            else
                return redirect()->back()->with('error','At least select one mail or sms');
        }
        else
        {
            $data['verification'] = 0;
            $data['verification_email']  = 0;
            $data['verification_phone']  = 0;
        }
        $mail['MAIL_MAILER'] = $data['mail_mailer'];
        $mail['MAIL_HOST'] = $data['mail_host'];
        $mail['MAIL_PORT'] = $data['mail_port'];
        $mail['MAIL_USERNAME'] = $data['mail_username'];
        $mail['MAIL_PASSWORD'] = $data['mail_password'];
        $mail['MAIL_ENCRYPTION'] = $data['mail_encryption'];
        $mail['MAIL_FROM_ADDRESS'] = $data['mail_from_address'];
        $mail['MAIL_FROM_NAME'] = $data['mail_from_name'];
        (new CustomController)->updateENV($mail);
        Setting::find(1)->update($data);
        return redirect()->back()->withStatus(__('verification Setting updated successfully..!!'));

    }

    public function update_content(Request $request)
    {
        $data = $request->all();
        $setting = Setting::first();
        if($request->hasFile('banner_image'))
        {
            (new CustomController)->deleteFile($setting->banner_image);
            $data['banner_image'] = (new CustomController)->imageUpload($request->banner_image);
        }
        $setting->update($data);
        return redirect()->back()->withStatus(__('Website Setting updated successfully.!'));
    }

    public function update_notification(Request $request)
    {
        $request->validate([
            'patient_app_id' => 'bail|required_if:patient_notification,1',
            'patient_auth_key' => 'bail|required_if:patient_notification,1',
            'patient_api_key' => 'bail|required_if:patient_notification,1',
            'doctor_app_id' => 'bail|required_if:doctor_notification,1',
            'doctor_auth_key' => 'bail|required_if:doctor_notification,1',
            'doctor_api_key' => 'bail|required_if:doctor_notification,1'
        ]);
        $data = $request->all();
        $patient = [
            'patient_app_id' => $data['patient_app_id'],
            'patient_auth_key' => $data['patient_auth_key'],
            'patient_api_key' => $data['patient_api_key'],
        ];
        (new CustomController)->updateENV($patient);
        $doctor = [
            'doctor_app_id' => $data['doctor_app_id'],
            'doctor_auth_key' => $data['doctor_auth_key'],
            'doctor_api_key' => $data['doctor_api_key'],
        ];
        (new CustomController)->updateENV($doctor);
        $data = $request->all();
        $data['patient_mail'] = $request->has('patient_mail') ? 1 : 0;
        $data['doctor_mail'] = $request->has('doctor_mail') ? 1 : 0;
        $data['doctor_notification'] = $request->has('doctor_notification') ? 1 : 0;
        $data['patient_notification'] = $request->has('patient_notification') ? 1 : 0;
        Setting::first()->update($data);
        return redirect()->back()->withStatus(__('Notification setting updated successfully..!!'));
    }

    public function update_licence_setting(Request $request)
    {
        $request->validate([
            'license_code' => 'required',
            'client_name' => 'required'
        ]);
        $api = new LicenseBoxExternalAPI();
        $result = $api->activate_license($request->license_code, $request->client_name);
        if ($result['status'] == true)
        {
            $id = Setting::find(1);
            $data = $request->all();
            $data['license_verify'] = 1;
            $id->update($data);
            return redirect('/login');
        }
        else
        {
            return redirect()->back()->with('error_msg', $result['message']);
        }
        return redirect('admin/setting');
    }

    public function update_static_page(Request $request)
    {
        Setting::first()->update($request->all());
        return redirect()->back()->withStatus(__('Setting updated successfully..!!'));
    }

    public function update_video_call_setting(Request $request)
    {
        Setting::first()->update($request->all());
        return redirect()->back()->withStatus(__('Setting updated successfully..!!'));
    }
}
