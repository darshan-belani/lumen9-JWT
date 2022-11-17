<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::get();
        return response()->json(
            [
                'message ' => 'Data got successfully',
                'data' => $users
            ]
        );
    }

    /**
     * @param $id
     * @return \Exception|\Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if ($user) {
                return response()->json(['message' => 'success', 'data' => $user]);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Exception|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
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
            $users = User::where('id', $id)->first();
            $users->first_name = $request->first_name;
            $users->last_name = $request->last_name;
            $users->email = $request->email;
            $users->mobile = $request->mobile;
            $users->save();
            return response()->json(
                [
                    'message ' => 'User updated successfully',
                    'data' => $users
                ]
            );
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
     * @param $id
     * @return \Exception|\Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            $user = User::where('id', $id)->first();
            $user->delete();
            if ($user) {
                return response()->json(['message' => 'Deleted successfully', 'data' => $user]);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}