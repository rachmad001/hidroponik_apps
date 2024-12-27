<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    //
    function create(Request $request){
        $id_user = $request->id_user;
        $validator = Validator::make($request->all(), [
            'ph' => 'required',
            'ppm' => 'required',
            'suhu' => 'required',
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
        $inserts = Data::create([
            'id_device' => $id_device,
            'ph' => $request->ph,
            'ppm' => $request->ppm,
            'suhu' => $request->suhu
        ]);

        return $this->responses(true, 'Berhasil menambahkan data');
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
        $data = Data::where('id_device', $id_device)->paginate(10);
        return response()->json($data);
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
