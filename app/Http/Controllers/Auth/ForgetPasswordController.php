<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPassword;
use App\Http\Requests\NewPassword;
use App\Mail\ForgotPassword;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;

class ForgetPasswordController extends Controller
{
    public function sendMail(ForgetPassword $request)
    {
        $userId = User::select('id')->where('email', $request->email)->first();

        if (!$userId) {
            return response()->json(['errors' => ['message' => "email is not exists"]], 401);
        }

        $url = URL::temporarySignedRoute('forgotpassword', now()->addMinute(10), ['email' => $request->email]);

        Mail::to($request->email)->send(new ForgotPassword($request->email, $url));

        return response()->json('success', 200);
    }

    public function show($email)
    {
        if (!request()->hasValidSignature()) {
            abort(401);
        } else {
            $userId = User::select('id')->where('email', $email)->first();
            return redirect('app/forgotpassword/email/' . $userId->id);
        }
    }

    public function store(NewPassword $request)
    {
        // dd($request->all());
        $newPassword = Hash::make($request->confirmPassword);
        User::where('id', $request->id)->update(['password' => $newPassword]);
        //return response()->json(['errors' => ['message' => "Invalid password"]], 401);

    }
}
