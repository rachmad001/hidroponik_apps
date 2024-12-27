<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Data extends Model
{
    //
    use SoftDeletes;
    protected $table = 'data_devices';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_device',
        'ph',
        'ppm',
        'suhu'
    ];
}
