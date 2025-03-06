<?php

namespace App\Http\Controllers;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index () {
        $user = UserModel::firstOrNew(
            [
                'username' => 'manager11',
                'nama' => 'Manager11',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ]
        );
        $user->username = 'manager12';

        $user->isDirty();
        $user->isDirty('username');
        $user->isDirty('nama');
        $user->isDirty('password');
        $user->isDirty('nama', 'password');

        $user->isClean();
        $user->isClean('username');
        $user->isClean('nama');
        $user->isClean('password');
        $user->isClean('nama', 'password');

        $user->save();

        $user->wasChanged();
        $user->wasChanged('username');
        $user->wasChanged(['username', 'level_id']);
        $user->wasChanged('name');
        $user->wasChanged(['nama', 'username']);
        dd($user->wasChanged(['nama', 'username']));
        return view('user', ['data' => $user]);
    }
}