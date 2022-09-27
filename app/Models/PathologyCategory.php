<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyCategory extends Model
{
    use HasFactory;

    protected $table = 'pathology_category';

    protected $fillable = ['name','status'];
}
