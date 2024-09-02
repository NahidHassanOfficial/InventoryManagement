<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Http\Requests\UserRequest;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function registerPage()
    {
        return view('pages.auth.registration-page');
    }

    public function loginPage()
    {
        return view('pages.auth.login-page');
    }

    public function resetPwdPage()
    {
        return view('pages.auth.send-otp-page');
    }
    public function OTPVerifyPage()
    {
        return view('pages.auth.verify-otp-page');
    }

    public function resetFormPage()
    {
        return view('pages.auth.reset-pass-page');
    }

    public function userRegister(UserRequest $request)
    {
        try {
            $requestvalidated = $request->validated();
            User::create([
                'firstName' => $requestvalidated['firstName'],
                'lastName' => $requestvalidated['lastName'],
                'email' => $requestvalidated['email'],
                'phone' => $requestvalidated['phone'],
                'password' => $requestvalidated['password'],
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something Wrong! Registration Failed',
            ], 400);
        }

    }

    public function verifyResetMail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'User Available',
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Not Found!',
            ], 401);
        }
    }

    public function sendOtp(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            $otp = rand(1001, 9999);

            Mail::to($email)->send(new OTPMail($otp));
            $user->update(['otp' => $otp]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ], 200);

        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Not Found',
            ], 401);
        }
    }

    public function verifyOtp(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $user = User::where('email', $email)->where('otp', $otp)->count();
        if ($user == 1) {
            $token = JWTToken::createTokenResetPWD($email);
            User::where('email', $email)->update(['otp' => 0]);

            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ], 200)->cookie('token', $token, 60 * 5);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Request Failed',
            ], 401);
        }

    }

    public function resetPwd(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', '=', $email)->update(['password' => $password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ], 200);

        } catch (Exception $e) {
            response()->json([
                'status' => 'failed',
                'message' => 'Password and Confirm Password do not match',
            ], 401);
        }
    }

    public function userLogin(Request $request)
    {
        $validatedRequest = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $userId = User::where('email', $validatedRequest['email'])
            ->where('password', $validatedRequest['password'])
            ->select('id')->first();

        if ($userId != null) {
            $token = JWTToken::createToken($request->input('email'), $userId->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successfully',
            ], 200)->cookie('token', $token, time() + 60 * 60 * 24 * 7);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Login Failed',
            ], 401);
        }
    }

    public function userLogout()
    {
        return redirect(route('loginPage'))->cookie('token', '', -1);
    }

    public function profile(Request $request)
    {
        $id = $request->header('id');
        return view('pages.dashboard.profile-page', compact('id'));
    }

    public function profileInfo(Request $request)
    {
        $user = User::find($request->header('id'));
        if ($user) {

            return response()->json(['status' => 'success', 'message' => 'Request Successfull', 'data' => $user], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 400);
        }

    }

    public function profileUpdate(UserRequest $request)
    {

        $validatedData = $request->validated();
        try {
            $user = User::find($request->header('id'));

            $user->fill($validatedData);
            if ($user->isDirty()) {
                $user->save();
                return response()->json(['message' => 'Request Successfull'], 201);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Request Failed!'], 404);
        }

    }
}
