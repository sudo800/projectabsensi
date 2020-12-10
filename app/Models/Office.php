<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;
    protected $table = "tb_office";
    protected $primaryKey = "office_id";
    protected $keyType = "string";
    protected $fillable = [
        'office_id', 'user_id', 'office_name', 'office_location', 'office_address', 'no_telp', 'fax',  'web_address',  'status',  'office_photo',  'email_address',  'latitude',  'longitude', 'radius',
    ];

    public function employies(){
        return $this->belongsToMany('App\Models\Employe', 'employe_id');
    }
}
