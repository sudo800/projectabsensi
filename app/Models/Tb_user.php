<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tb_user extends Model
{
    // protected $table ='tb_users';
    protected $primaryKey = "user_id";
    use HasFactory;
    protected $fillable = [
        'user_id', 'fullname', 'email', 'password', 'username', 'user_photo', 'status','service_id','expired_date','role_id',
    ];
}
