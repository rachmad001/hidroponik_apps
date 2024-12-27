<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    //
    use SoftDeletes;
    protected $table = 'user';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'alamat',
        'no_handphone',
        'email',
        'password',
        'token'
    ];
}
