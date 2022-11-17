<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param Request $request
     * @return \Exception|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                'mobile' => 'required',
            ], [
                    'first_name.required' => ('Please enter first name'),
                    'last_name.required' => ('Please enter last name'),
                    'email.required' => ('Please enter email'),
                    'mobile.required' => ('Please enter mobile'),
                ]
            );
            if ($validator->fails()) {
                return $validator->errors();
            }
            $users = new User();
            $users->first_name = $request->first_name;
            $users->last_name = $request->last_name;
            $users->email = $request->email;
            $users->password = Hash::make($request->password);
            $users->mobile = $request->mobile;
            $users->save();
            return response()->json([
                'message ' => 'User created successfully',
                'data' => $users
            ]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
     * @param Request $request
     * @return \Exception|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                'password' => 'required',
            ], [
                    'email.required' => ('Please enter email'),
                    'password.required' => ('Please enter mobile'),
                ]
            );
            if ($validator->fails()) {
                return $validator->errors();
            }
            $credentials = $request->only(['email', 'password']);

            if (!$token = Auth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return $this->respondWithToken($token);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = [
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ];
        return response()->json([
            'message' => 'Login successfully',
            'data' => $user
        ]);
    }
}
