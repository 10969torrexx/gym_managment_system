<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class OneTimePasswordController extends Controller
{
    //
    public function index($email)
    {
        $ifEmailExist = OneTimePassword::where('email', $email)->first();
        $otp = null;
        if(!$ifEmailExist){
            // Check if OTP already exists in session
            if (!session()->has('otp')) {
                $otp = mt_rand(100000, 999999);
                session(['otp' => $otp]); // Store OTP in session
        
                $emailOtp = OneTimePassword::create([
                    'otp' => $otp,
                    'email' => $email
                ]);
        
                // Send OTP email
                Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
                    $message->to($email)->subject('Your OTP Code');
                });
            } else {
                $otp = session('otp'); // Retrieve OTP from session
            }
        }
        return view('onetimepassword.index', compact('email', 'otp'));
    }
    public function verify(Request $request)  {
        $request->validate([
            'otp' => 'required|numeric'
        ]);
        $otp = $request->otp;
        $email = $request->email;
        $emailOtp = OneTimePassword::where('email', $email)->first();
        if ($emailOtp->otp == $otp) {
            $user = User::where('email', $email)->first();
            if(!$user){
                $user = User::create([
                    'email' => $email,
                    'name' => Session::get('userData')['name'],
                    'picture' => !empty(Session::get('userData')['picture']) ? Session::get('userData')['picture'] : null,
                    'role' => !empty(Session::get('userData')['role']) ? Session::get('userData')['role'] : 0,
                    'password' => Hash::make(!empty(Session::get('userData')['password']) ? Session::get('userData')['password'] : 'ExampleString')
                ]);
            }
            OneTimePassword::where('email', $email)->delete();
            session()->flush();
            Auth::login($user);
            return response()->json([
                'status' => 200, // 'error
                'message' => 'OTP verified successfully'
            ]);
        } else {
            return response()->json([
                'status' => 500, // 'error
                'message' => 'Invalid OTP'
            ]);
        }
    }
}
