<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blood_donate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "blood_donate";

    protected $fillable = ['name', 'address', 'phone', 'location','dob', 'gender', 'blood_group'];
    
}
