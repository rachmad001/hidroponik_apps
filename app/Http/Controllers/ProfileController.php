<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    //
    function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'no_handphone' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response($this->responses(false, implode(",", $validator->messages()->all())), 400);
        }

        //check email registered
        $find_email = Profile::where('email', $request->email)->count();
        if ($find_email > 0) {
            return response($this->responses(false, 'Email telah terdaftar'), 409);
        }

        $token = $this->generateToken();
        $inserts = Profile::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_handphone' => $request->no_handphone,
            'email' => $request->email,
            'password' => md5(sha1($request->password)),
            'token' => $token
        ]);

        return $this->responses(true, 'Berhasil mendaftar');
    }

    function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response($this->responses(false, implode(",", $validator->messages()->all())), 400);
        }

        $profile = Profile::where('email', $request->email)->where('password', md5(sha1($request->password)));
        //check valid
        if($profile->count() == 0){
            return response($this->responses(false, 'Email atau password salah'), 401);
        }

        return $this->responses(true, 'Berhasil login', $profile->get()[0]);
    }

    function generateToken()
    {
        $token = Str::random(30);
        $find_token = Profile::where('token', $token)->count();
        if ($find_token > 0) {
            return $this->generateToken();
        }
        return $token;
    }

    function responses($status, $message, $data = array())
    {
        return json_encode(array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        ), JSON_PRETTY_PRINT);
    }
}
