<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|',
            'nama' => 'required|',
            'password' => 'required|min:5|confirmed',
            'level_id' => 'required|',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $user = UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
            'image' => $request->image->hashName(),
        ]);

        return response()->json([
            'status' => true,
            'user' => $user,
        ], 201);

        return response()->json([
            'status' => false,
            'message' => 'User registration failed',
        ], 422);
    }
}
