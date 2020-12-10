<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = "tb_role";
    protected $keyType = "string";
    protected $primarykey = "role_id";

    protected $fillable = [
        'role_id', 'role_name', 'user_id', 'description_role',
    ];
}
