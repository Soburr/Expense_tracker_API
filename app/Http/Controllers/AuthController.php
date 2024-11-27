<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request) {
         $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
         ]);


         $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
         ]);

         $token = $user->createToken('API Token')->accessToken;

         return response()->json(['token' => $token], 201);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
         ]);

        if (auth()->attempt($request->only('email', 'password'))) {
           $token = auth()->user()->createToken('API Token')->accessToken;

           return response()->json(['token' => $token], 200);
        }
           return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function userInfo() {
        try {
            $userDataList = User::latest()->paginate(10);
            $data = [];
            $data['response_code'] = '200';
            $data['status'] = 'success';
            $data['message'] = 'Users List';
            $data['data_user_list'] = $userDataList;

            return response()->json($data);
        } catch(Exception $e) {
            Log::info($e);

            $data = [];
            $data['response_code'] = '401';
            $data['status'] = 'error';
            $data['message'] = 'Failed Login';

            return response()->json($data);
        }
    }
}


// Client ID ........ 9d972e39-a280-4272-8972-a51eb9c6529f
//   Client secret ...... 4LzcejwybQrG70AAvlRa4E2FwIFQ6uvfjfIPRn8m
