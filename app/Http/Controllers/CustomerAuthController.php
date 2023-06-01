<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{

    public function login(Request $request)
    {
        // Validate login request

        if (auth()->attempt($request->only('email', 'password'))) {
            // Authentication successful
            $user = auth()->user();
            $token = $user->createToken('Customer Access Token')->accessToken;
            return response()->json(['token' => $token]);
        } else {
            // Authentication failed
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function forgotPassword(Request $request)
    {
        // Send password reset email to the customer
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset link sent'])
            : response()->json(['error' => 'Unable to send reset link'], 400);
    }

    public function resetPassword(Request $request)
    {
        // Reset the customer's password
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $response === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'])
            : response()->json(['error' => 'Unable to reset password'], 400);
    }

    public function logout(Request $request)
    {
        // Revoke the customer's access token
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logout successful']);
    }

}
