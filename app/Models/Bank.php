<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = "tb_bank";
    protected $primaryKey = "bank_id";
    protected $keyType = "string";
    protected $fillable = [
        'bank_id', 'user_id', 'bank_name', 'alias', 'no_rekening',
    ];

}
