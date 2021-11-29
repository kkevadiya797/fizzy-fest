<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    public $successStatus = 200;

    public function store(Request $request)
    {
        $query = User::select('email', 'password','email_verified_at')->where([['email', '=', $request->email],['email_verified_at', '!=', null],['deleted_at','=',null]]);
        $isUserExists = $query->exists();

        $user = $query->first();

        if (!$isUserExists || !(Hash::check($request->password, $user->password))) {
            return response()->json(['errors' => ['message' => "Invalid email or password"]], 401);
        }

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => env('OTHER_CLIENT_ID'),
            'client_secret' => env('OTHER_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*'
        ]);

        $tokenRequest = Request::create('/oauth/token', 'post');
        return Route::dispatch($tokenRequest);
    }
}
