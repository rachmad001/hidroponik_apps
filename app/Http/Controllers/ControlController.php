<?php

namespace App\Http\Controllers;

use App\Models\Control;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ControlController extends Controller
{
    //
    function create(Request $request){
        $id_user = $request->id_user;
        $validator = Validator::make($request->all(), [
            'ph_up' => 'required',
            'ph_down' => 'required',
            'nutrisi' => 'required',
            'heater' => 'required',
            'pump_mix' => 'required',
            'security_key' => 'required',
            'id_user' => 'required'
        ]);

        if($validator->fails()){
            return response($this->responses(false, implode(",", $validator->messages()->all())), 400);
        }

        //check valid security_key
        $find_device = Device::where('security_key', $request->security_key);
        if($find_device->count() == 0){
            return response($this->responses(false, 'Device tidak ditemukan'), 404);
        }
        if($find_device->where('id_user', $request->id_user)->count() == 0){
            return response($this->responses(false, 'Tidak memiliki akses'), 403);
        }

        $id_device =  Device::where('security_key', $request->security_key)->where('id_user', $request->id_user)->first()->id;

        $data = [
            'id_device' => $id_device,
            'ph_up' => $request->ph_up,
            'ph_down' => $request->ph_down,
            'nutrisi' => $request->nutrisi,
            'heater' => $request->heater,
            'pump_mix' => $request->pump_mix,
        ];

        $control = Control::where('id_device', $id_device);
        if($control->count() == 0){
            $inserts = Control::create($data);
        }else {
            $updates = $control->update($data);
        }

        return $this->responses(true, 'Berhasil memperbarui data');
    }

    function list(Request $request){
        $id_user = $request->id_user;
        $validator = Validator::make($request->all(), [
            'security_key' => 'required',
            'id_user' => 'required'
        ]);

        if($validator->fails()){
            return response($this->responses(false, implode(",", $validator->messages()->all())), 400);
        }

        //check valid security_key
        $find_device = Device::where('security_key', $request->security_key);
        if($find_device->count() == 0){
            return response($this->responses(false, 'Device tidak ditemukan'), 404);
        }
        if($find_device->where('id_user', $request->id_user)->count() == 0){
            return response($this->responses(false, 'Tidak memiliki akses'), 403);
        }

        $id_device =  Device::where('security_key', $request->security_key)->where('id_user', $request->id_user)->first()->id;
        $data = Control::where('id_device', $id_device)->get();
        return $this->responses(true, 'Berhasil mendapatkan data', $data);
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
