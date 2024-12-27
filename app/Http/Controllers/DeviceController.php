<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    //
    function create(Request $request){
        // \Log::info($request->all());
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'keterangan' => 'nullable',
            'id_user' => 'required'
        ]);

        if($validator->fails()){
            return response($this->responses(false, implode(",", $validator->messages()->all())), 400);
        }

        $security_key = $this->generate_security_key($request->id_user);
        $inserts = Device::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'security_key' => $security_key,
            'id_user' => $request->id_user
        ]);

        return $this->responses(true, 'Berhasil menambahkan data');
    }

    function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'security_key' => 'required',
            'nama' => 'required',
            'keterangan' => 'nullable',
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

        $updates = Device::where('security_key', $request->security_key)->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return $this->responses(true, 'Berhasil memperbarui data');
    }

    function delete(Request $request){
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

        $deletes = Device::where('security_key', $request->security_key)->delete();
        return $this->responses(true, 'Berhasil menghapus data');

    }

    function list(Request $request){
        $id_user = $request->id_user;
        $devices = Device::where('id_user', $id_user)->get();
        return $this->responses(true, 'Berhasil mendapatkan data', $devices);
    }

    function generate_security_key($id_user){
        $security_key = Str::random(10);
        $find_device = Device::where('security_key', $security_key)->where('id_user', $id_user)->count();
        if($find_device > 0){
            return $this->generate_security_key($id_user);
        }
        return $security_key;
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
