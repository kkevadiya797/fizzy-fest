<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Register;
use App\Models\UserType;
use App\User;
use App\Mail\EmailValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    public function register(Register $request)
    {
        $url = URL::temporarySignedRoute('emailVarification', now()->addMinute(10), ['email' => $request->email]);

        // Mail::to($request->email)->send(new EmailVarification($request->email, $url));
        Mail::to($request->email)->send(new EmailValidate($request->email, $url));

        $userTypeId = UserType::select('id')->where('type', $request->userType)->first();

        User::create([
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'user_type_id' => $userTypeId->id
        ]);
        
        return response()->json('Success', 200);
    }

    public function emailVarifiy($email)
    {
        if (!request()->hasValidSignature()) {
            abort(401);
        } else {
            echo $email;
            User::where('email',$email)->update(['email_verified_at' => date('Y-m-d H:i:s')]);
            echo "Email successfully Varifiy";
        }

    }
}
