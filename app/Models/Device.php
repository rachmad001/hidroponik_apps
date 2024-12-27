<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    //
    use SoftDeletes;
    protected $table = 'device';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'keterangan',
        'security_key',
        'id_user'
    ];
}
