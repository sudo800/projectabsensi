<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
// class User extends Model
// class User extends Model implements Authenticatable
{
    // protected $table = "tb_users";
    protected $primaryKey = "user_id";
    protected $keyType = "string";
    use HasFactory, Notifiable;
    protected $fillable = [
        'user_id', 'fullname', 'email', 'password', 'username', 'user_photo', 'status','service_id','expired_date','role_id',
    ];

    public function role(){
        return $this->HasOne(role::class, 'user_id');
    }

    public function billing(){
        return $this->HasOne(billing::class, 'user_id');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
