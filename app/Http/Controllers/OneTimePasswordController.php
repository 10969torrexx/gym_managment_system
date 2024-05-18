<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OneTimePassword;
use App\Models\User;

class OneTimePasswordController extends Controller
{
    //
    public function index($email)
    {
        $otp = rand(100000, 999999);
        OneTimePassword::create([
            'email' => $email,
            'otp' => $otp
        ]);
        return view('otp.index', compact('email', 'otp'));
    }
}
