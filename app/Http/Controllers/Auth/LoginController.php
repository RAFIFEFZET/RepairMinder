<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('user_view.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'siswa') {
                Alert::success('Mantap', 'Anda berhasil login!');
                return redirect('/');
            }
            if ($user->role === 'guru') {
                return redirect()->route('home.guru');
            }
            if ($user->role === 'admin') {
                return redirect('/sija');
            }
            if ($user->role === 'teknisi' && optional($user->zoneUser)->zone_name === 'sija') {
                return redirect('/sija');
            }
            if ($user->role === 'teknisi' && optional($user->zoneUser)->zone_name === 'dkv') {
                return redirect('/dkv');
            }
            if ($user->role === 'teknisi' && optional($user->zoneUser)->zone_name === 'sarpras') {
                return redirect('/sarpras');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
