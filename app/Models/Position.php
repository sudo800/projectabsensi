<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $table = "tb_position";
    protected $primaryKey = "position_id";
    protected $keyType = "string";
    protected $fillable = [
        'position_id', 'user_id', 'position_name', 'position_description',
    ];

    public function employe(){
        return $this->HasOne(Employe::class, 'position_id');

    }
}
