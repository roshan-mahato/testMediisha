<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = ['company_white_logo','phone','email','company_logo','company_favicon','currency_symbol','currency_code','color','timezone','business_name','cod','paypal','stripe','razor','flutterwave','paystack','stripe_public_key','stripe_secret_key','paypal_sandbox_key','paypal_producation_key','razor_key','flutterwave_encryption_key','default_commission','default_base_on','map_key','verification','using_mail','using_msg','twilio_auth_token','twilio_acc_id','twilio_phone_no','mail_mailer','mail_host','mail_port','mail_username','mail_password','mail_encryption','mail_from_address','mail_from_name','flutterwave_key','paystack_public_key','cancel_reason','website_color','pharmacy_commission','radius','auto_cancel','patient_notification','doctor_notification','patient_app_id','patient_auth_key','patient_api_key','doctor_app_id','doctor_auth_key','doctor_api_key','patient_mail','doctor_mail','license_code','client_name','license_verify','language','playstore','appstore','privacy_policy','about_us','agora_app_id','agora_app_certificate','banner_image','banner_url','pathologist_commission'];

    protected $appends = ['companyWhite','logo','favicon'];

    public function getcompanyWhiteAttribute()
    {
        return url('images/upload').'/'.$this->attributes['company_white_logo'];
    }

    public function getlogoAttribute()
    {
        return url('images/upload').'/'.$this->attributes['company_logo'];
    }

    public function getfaviconAttribute()
    {
        return url('images/upload').'/'.$this->attributes['company_favicon'];
    }
}