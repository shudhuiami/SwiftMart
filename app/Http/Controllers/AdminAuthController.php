<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class AdminAuthController extends Controller
{

    public function broker()
    {
        return Password::broker('admins');
    }

    public function login(Request $request)
    {
        $inputData = $request->input();
        $validator = Validator::make($inputData, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = Admin::where('email', $inputData['email'])->first();
        $isRemember = $input['remember'] ?? 0;

        if(!empty($admin)){
            if (Hash::check($inputData['password'], $admin->password)) {
                if ($isRemember == 1) {
                    Passport::personalAccessTokensExpireIn(Carbon::now()->addDay(7));
                } else {
                    Passport::personalAccessTokensExpireIn(Carbon::now()->addDay(1));
                }

                $access_token = $admin->createToken('authToken')->accessToken;

                return response()->json(['data' => $admin, 'token' => $access_token], 200);

            }
        }

        return response()->json(['errors' => ['email' => 'Invalid credentials']], 422);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $status = $this->broker()->sendResetLink(
            $request->only('email')
        );

        dump($status, Password::RESET_LINK_SENT);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email'], 200)
            : response()->json(['errors' => ['email' => 'Unable to send reset link']], 422);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken($request->input('token'));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successful'], 200)
            : response()->json(['errors' => ['email' => 'Unable to reset password']],422);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

}
