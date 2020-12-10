<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPlacement extends Model
{
    use HasFactory;
    protected $table = "tb_work_placement";
    protected $fillable = [
        'user_id', 'office_id', 'employe_id',
    ];
}
