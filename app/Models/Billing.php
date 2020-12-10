<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;
    protected $table = "tb_billing";
    protected $primaryKey = "billing_id";
    protected $keyType = "string";
    protected $fillable = [
        'billing_id', 'user_id', 'verify_by', 'expired_date', 'service_id','billing_price', 'billing_status', 'date_transaction','billing_photo', 'billing_total','billing_method', 'sub_total','qty_month', 'billing_max_user',
    ];

    public function service(){
        return $this->belongsTo(service::class, 'service_id');
    }

    public function user(){
        return $this->belongsTo(user::class, 'user_id');
    }
}
