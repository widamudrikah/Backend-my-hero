<?php

namespace App\Http\Controllers;

use App\Helpers\FormatUserHelper;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $dataUser = User::get()
                    ->map(function($dataUser){
                        return $this->format($dataUser);
                    });
        return $this->result($dataUser, "Berhasil mendapatkan data user");
    }

    public function result($dataUser, $message)
    {
        return response()->json([
            'Status'        => true,
            'Message'       => $message,
            'Data'          => $dataUser
        ]);
    }

    public function format($dataUser)
    {
        return[
            'user_id'      => $dataUser->id,
            'name'         => $dataUser->name,
            'email'        => $dataUser->email,
            'role'         => $dataUser->role,
            'registered'   => date("l, d F Y", strtotime($dataUser->created_at)),
        
        ];
    }

    public function error($status, $message)
    {
        return response()->json([
            'Status'    => $status,
            'Message'   => $message
        ]);
    }
}
