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

        //  $date = Carbon::now();
        //  $joinDate = $date->toDateTimeString();

         $user = new User();

         $user->name = $request->name;
         $user->email = $request->email;
         $user->password = Hash::make($request->password);
         $user->save();

         $data = [];
         $data['response_code'] = '200';
         $data['status'] = 'success';
         $data['message'] = 'successful registration';

         return response()->json($data);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
         ]);

        try {
            $email = $request->email;
            $password = $request->password;

            if (Auth::attempt(['email' => $email, 'password' => $password])) {
              $user = Auth::user();
              $accessToken = $user->createToken($user->email)->accessToken;

              $data = [];
              $data['response_code'] = '200';
              $data['status'] = 'success';
              $data['message'] = 'successful Login';
              $data['user_info'] = $user;
              $data['token'] = $accessToken;

              return response()->json($data);

            } else {
              $data = [];
              $data['response_code'] = '401';
              $data['status'] = 'error';
              $data['message'] = 'Unauthorized';

              return response()->json($data);
            }
        } catch(Exception $e) {
              Log::info($e);

              $data = [];
              $data['response_code'] = '401';
              $data['status'] = 'error';
              $data['message'] = 'Failed Login';

              return response()->json($data);
        }
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
