<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallCenter extends Model
{
    use HasFactory;

    protected $table = 'callcenter';

    protected $fillable = ['name','user_id','hospital_id','education','certificate','experience','dob','gender','image','start_time','end_time','status'];

    protected $appends = ['fullImage'];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital');
    }

    protected function getFullImageAttribute()
    {
        return url('images/upload').'/'.$this->image;
    }
}
