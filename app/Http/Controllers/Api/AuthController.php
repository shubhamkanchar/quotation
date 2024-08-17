<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\BusinessModel;
use App\Models\User;
use App\Models\UserBusiness;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $request->validated();
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone
            ];

            $user = User::create($data);
            $firstBusiness = BusinessModel::first();

            if ($firstBusiness) {
                $userBusiness = new UserBusiness();
                $userBusiness->user_id = $user->id;
                $userBusiness->business_id = $firstBusiness->id;
                $userBusiness->save();
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successfull'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong'
            ], 400);
        }
    }
}
