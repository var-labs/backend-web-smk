<?php

namespace App\Http\Controllers;

use App\Models\tb_admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function firstAuth(request $request)
    {
        $token = $request->validate([
            "token" => "required"
        ])["token"];

        $result = User::where('token', $token)->first();
        if ($result !== null) {
            $nToken = $token;
            return redirect()->route('guest.login', ['token' => $token]);
        } else {
            return back()->with('tokenError', 'Incorrect Token');
        }
    }

    public function tokenPage()
    {
        return view("admin.guest.token");
    }

    public function checkTokenUrl($token)
    {
        $result = User::where('token', $token)->first();
        return $result;
    }

    public function getToken($token)
    {
        return $token;
    }

    public function loginPage($token,Request $request)
    {
        if(Auth::check() && (Auth::getUser()["token"] == $token)) {
            $user_id = Auth::getUser();
//            return ["user"=>$user_id["token"],"session"=>session()->all()];
            $request->session()->put('token', $token);
            $request->session()->put('status', 'true');
            return redirect()->route('dashboard', ['token' => $user_id["token"]]);
        }
        return view('admin.guest.login', [
            'token' => $token,
        ]);
    }

    public function login(Request $request, $token)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if($request->has("cookie")) {
            $cookie = true;
        } else {
            $cookie = false;
        }

        Log::info($cookie);
        $user = User::where('email', $email)
            ->where('token', $token)
            ->first();

        if ($user && Auth::attempt(["email"=>$email,"password"=>$password],$cookie)) {
            if (password_verify($password, $user->password)) {
                // Set session user
                Auth::logoutOtherDevices($password);
                $request->session()->put('user', $user);
                $request->session()->put('xaf', $password);
                $request->session()->put('token', $token);
                $request->session()->put('status', 'true');

                // Redirect ke dashboard
                return redirect()->route('dashboard', ['token' => $token]);
            } else {
                // Password tidak sesuai
                return back()->with('loginError', 'Login Failed. Email / Password Invalid');
            }
        } else {
            // User tidak ditemukan
            return back()->with('loginError', 'Login Failed. Email / Password Invalid');
        }
    }

    public function logout(Request $request)
    {
        Auth::logoutCurrentDevice();
        session()->invalidate();
        session()->regenerate();
        session()->flush();
        $_SESSION = [];
        return redirect()->route('guest.token');
    }
}
