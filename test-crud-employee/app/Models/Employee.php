<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    
    protected $fillable = ['nomor', 'nama', 'jabatan', 'talahir','photo_upload_path', 'created_by', 'updated_by'];

    public $timestamps = false;
}
