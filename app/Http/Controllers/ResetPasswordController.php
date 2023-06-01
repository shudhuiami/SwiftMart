<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{

    public function showResetForm(Request $request, $token = null)
    {
        return view('Admin.Auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }
}
