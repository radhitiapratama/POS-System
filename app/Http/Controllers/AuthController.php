<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view("pages.auth.index");
    }

    public function handleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => "required",
            'password' => "required|min:8"
        ], [
            'username.required' => "Username wajib diisi.",
            'password.required' => "Password wajib diisi.",
            'password.min' => "Password minimal 8 karakter",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('cashier');
        }

        return redirect()->back()->withInput()
            ->with("error", "Gagal")
            ->with("error_message", "Username/Password salah");
    }
}
