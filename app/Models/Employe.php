<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Position;

class Employe extends Model
{
    use HasFactory;
    protected $table = "tb_employe";
    protected $primaryKey = "employe_id";
    protected $keyType = "string";
    protected $fillable = [
        'employe_id', 'user_id','position_id', 'employe_name', 'employe_photo', 'employe_email', 'employe_nik', 'employe_address', 'employe_place_of_birthday','employe_date_of_birthday', 'employe_gender', 'username','password',
    ];

    public function position(){
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function offices(){
        return $this->belongsToMany(Office::class, 'tb_work_placement','employe_id','office_id');
    }

    // public function officies(){
    //     return $this->belongsToMany('App\Models\Office', 'office_id');
    // }


}
