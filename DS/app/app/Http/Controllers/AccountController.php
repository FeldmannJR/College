<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Middleware\CheckUserRole;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{


    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'bail|required|exists:users|nonroot'
        ]);

        User::find($request->input('id'))->delete();
        return response()->json(['success' => true], 200);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|unique:users|min:3|max:32',
            'password' => 'required|string|min:5|max:32',
            'user_role' => 'required|enum_value:' . UserRole::class . ',false|not_in:' . UserRole::Root
        ]);
        $data['user_role'] = UserRole::getInstance((int)$data['user_role']);
        $data['password'] = \Hash::make($data['password']);
        return User::create($data);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:users',
            'username' => 'required_without_all:user_role,password|string|unique:users|min:3|max:32',
            'password' => 'required_without_all:username,user_role|string|min:5|max:32',
            'user_role' => 'bail|required_without_all:username,password|enum_value:' . UserRole::class . ',false|not_in:' . UserRole::Root
        ]);


        $user = User::find($data['id']);
        if (array_key_exists('username', $data)) {
            $user->username = $data['username'];
        }
        if (array_key_exists('user_role', $data)) {
            // Não deixa tirar o role  do root, caso consiga não consegue mais reverter sem mexer no DB
            if ($user->user_role->is(UserRole::Root)) {
                return response()->json(['message' => '', 'errors' => ['id' => ['Você não modificar o usuario root!']]], 422);
            }
            $user->user_role = UserRole::getInstance((int)$data['user_role']);
        }
        if (array_key_exists('password', $data)) {
            $user->password = \Hash::make($data['password']);
            $user->setRememberToken(null);
            // Limpando a sessão do database / se for usar redis isso aqui n funciona mais
            \DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $user->save();
        return response()->json($user);
    }


}
