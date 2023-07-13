<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FormatUserHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registerUser(Request $req)
    {

        $validate = Validator::make($req->all(), [
            'name'  => 'required',
            'email'  => 'required|unique:users',
            'password'  => 'required',
        ], FormatUserHelper::messageError());

        if ($validate->fails()) {
            $val = $validate->errors()->all();
            $message = $val[0];
            return FormatUserHelper::resultStatus(false, $message, null);
        }

        $dataUser = User::create([
            'name' => $req->name,
            'email' => $req->email, //Auto Hash karena cari $casts pada model User
            'password' => $req->password,
        ]);

        $message = "Berhasil regis akun";
        $userRegister = FormatUserHelper::resultUser($dataUser->id);
        return FormatUserHelper::resultStatus(true, $message, $userRegister);
    }

    public function loginUser(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'email'  => 'required',
            'password'  => 'required'
        ], FormatUserHelper::messageError());

        if ($validate->fails()) {
            $val = $validate->errors()->all();
            $message = $val[0];
            return FormatUserHelper::resultStatus(false, $message, null);
        }

        $dataUser = User::where('email', $req->email)->first();

        if ($dataUser) {
            if (password_verify($req->password, $dataUser->password)) {
                $message = "Welcome $dataUser->name";

                $userLogin = FormatUserHelper::resultUser($dataUser->id);

                return FormatUserHelper::resultStatus(true, $message, $userLogin);
            } else {
                $message = "sesat";
            }
        } else {
            $message = "cek email lagi coba";
        }

        return FormatUserHelper::resultStatus(false, $message, null);
    }
};
