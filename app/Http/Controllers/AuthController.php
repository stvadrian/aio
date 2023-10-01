<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function viewLogin()
    {
        if (Auth::check() || Auth::viaRemember()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function loginAttempt(Request $request)
    {
        if (isset($request->honeypot)) {
            return redirect('/')->with(['honeypot' => "Success"]);
        }

        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $remember = (request()->remember) ? true : false;

        if (auth()->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }
        return back()->with(['login-failed' => "The credentials doesn't match any of our records."])->withInput($request->input());
    }

    public function viewResetPassword()
    {
        return view('auth.reset_password');
    }

    public function resetPassword(Request $request)
    {
        if (isset($request->honeypot)) {
            return redirect('/')->with('recover-password', "Success");
        }

        $request->validate(['mobile' => 'required']);

        $mobile = $request['mobile'];
        $user = User::where('mobile_user', $mobile)->first();

        if ($user) {
            $name = $user->nm_karyawan;
            $pass_token = csrf_token();
            DB::table('password_reset')->insert([
                'mobile' => $mobile,
                'token' => $pass_token,
                'created_at' => date('Y-m-d')
            ]);

            $link = "$this->baselink/$this->rootname/recover-password/$pass_token";
            $message = "Hi $name, You recently requested to reset the password for your Portal AIO account. Please click this link within 24 hours: $link. If you did not request a password reset, please ignore this message or reply to let us know.";
            // if ($this->sendWABlas($mobile, $message, 'text')) {
            return back()->with('sended', "Please kindly check your WhatsApp at $mobile for creating new password.");
            // }
        } else {
            return back()->with('mobile-not-found', "WhatsApp number doesn't match any of our records.");
        }
    }

    public function viewRecoverPassword($pass_token)
    {
        $user = DB::table('password_reset')->where('token', $pass_token)->first();
        $curdate = date('Y-m-d');
        if ($user) {
            $token_date = date('Y-m-d', strtotime($user->created_at));

            if ($curdate == $token_date) {
                return view('auth.recover_password');
            }
        }
        return redirect('error/404');
    }

    public function recoverPassword(Request $request, $token)
    {
        if (isset($request->honeypot)) {
            return redirect('/')->with(['recover-password' => "Success"]);
        }

        $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);

        $user = DB::table('password_reset')->where('token', $token)->first();
        if ($user) {
            try {
                DB::table('password_reset')->where('token', $token)->delete();
            } catch (\Throwable $th) {
                return back()->with(['error' => 'An error occurred. Please try again later.']);
            }

            try {
                User::where('mobile_user', $user->mobile)->update([
                    'password' => bcrypt($request->password)
                ]);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'An error occurred changing your password. Please try again later.']);
            }
            return redirect('/')->with(['recover-password' => 'Password has been changed. Please login now.']);
        }
        return redirect('error/500');
    }

    public function viewChangePassword(Request $request)
    {
        if (isset($request->honeypot)) {
            return redirect('/')->with(['recover-password' => "Success"]);
        }
        if (auth()->check()) {
            if ($request->has('change_pass')) {
                $request->validate([
                    'password' => 'required|confirmed|min:6'
                ]);
                $new_password = bcrypt($request->password);
                try {
                    User::whereId(auth()->user()->id)->update([
                        'password' => $new_password
                    ]);
                    return redirect('/');
                } catch (\Throwable $th) {
                    return redirect()->back()->with(['error' => 'Failed updating password, please try again.']);
                }
            }
            return view('auth.change_password');
        }
        return redirect('error/404');
    }

    public function AFKManager(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->put('afk', __('resp_msg.afk_msg'));
        return true;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if (!isset($request->afk)) {
            return redirect('/');
        }
    }
}
