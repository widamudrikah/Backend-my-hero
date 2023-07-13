<?php

namespace App\Helpers;

use App\Models\User;

class FormatUserHelper
{

    public static function formatResultUser($userLogin)
    {
        
        return [
            'user_id'      => $userLogin->id,
            'name'         => $userLogin->name,
            'email'        => $userLogin->email,
            'role'         => $userLogin->role,
            'registered'   => date("l, d F Y", strtotime($userLogin->created_at)),
        ];
    }

    public static function resultUser($user_id)
    {
        // return User:: pake ini juga bisa
        $userLogin = User::where('id', $user_id)
            ->get()
            ->map(function ($userLogin) {
                return FormatUserHelper::formatResultUser($userLogin);
            });
        return $userLogin;
    }

    public static function resultStatus($status, $message, $dataUser = null)
    {
        if ($dataUser !== null) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'user' => $dataUser
            ]);
        } else {
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }
    }

    public static function messageError()
    {
        $msgError = [
            'name.required' => "Nama masih kosong",
            'email.required' => "Email masih kosong",
            'password.reqiured' => "Password masih kosong",
        ];
        return $msgError;
    }
}
