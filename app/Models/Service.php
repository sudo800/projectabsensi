<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table = "tb_service";
    protected $primaryKey = "service_id";
    protected $keyType = "string";
    protected $fillable = [
        'service_id', 'user_id', 'service_name', 'service_description', 'service_price', 'min_user',
    ];

    public function billing(){
        return $this->belongsTo(billing::class, 'service_id');
    }

}


