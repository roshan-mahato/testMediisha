<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodBank extends Model
{
    use HasFactory;

    protected $table = 'bloodbank';

    protected $fillable = ['image',];

    // protected $appends = ['fullImage'];

    protected function getFullImageAttribute()
    {
        return url('images/upload').'/'.$this->image;
    }
}
