<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Control extends Model
{
    //
    use SoftDeletes;
    protected $table = 'control_device';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_device',
        'ph_up',
        'ph_down',
        'nutrisi',
        'heater',
        'pump_mix'
    ];
}
