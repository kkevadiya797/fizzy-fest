<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePassword;
use App\Http\Requests\UserProfile;
use App\Models\DeletedAccount;
use App\Models\Report;
use App\Models\UserType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getUser()
    {
        return response()->json(Auth::user(), 200);
    }

    public function detail()
    {
        $userId = Auth::user()->id;
        $userDetail = User::select('username', 'name', 'email', 'user_type_id', 'id')->where('id', $userId)->first();
        $userType = UserType::select('type')->where('id', $userDetail->user_type_id)->get();
        $userDetail['type'] = $userType[0]->type;
        $userDetail['hasImage'] = Storage::exists("/public/avatar/" . $userId . ".jpg");
        $userDetail['imageUrl'] = "";
        return response()->json(['userDetail' => $userDetail], 200);
    }

    public function changeDetail(UserProfile $request)
    {
        $userId = Auth::user()->id;
        User::where('id', $userId)->update(['name' => $request->name]);

        if ($request->hasFile('image')) {
            $request->file('image')->storeAs('public/avatar', $userId . ".jpg");
        }

        return response()->json('success', 200);
    }

    public function changePassword(ChangePassword $request)
    {
        $userId = Auth::user()->id;
        $userCurrentPassword = User::select('password')->where('id', $userId)->first();

        if (Hash::check($request->oldPassword, $userCurrentPassword->password)) {
            $encryptNewPassword = Hash::make($request->newPassword);

            User::where('id', $userId)->update(['password' => $encryptNewPassword]);
            return response()->json('success', 200);
        } else {
            return response()->json(['errors' => ['message' => "Invalid password"]], 400);
        }
    }

    public function deleteAccount(Request $request)
    {
        DeletedAccount::create([
            'reason' => $request->reason,
            'user_id' => Auth::user()->id
        ]);
        User::find(Auth::user()->id)->delete();

        return response()->json('deleted Account successfully', 200);
    }

    public function userIndex()
    {
        $userId = Auth::user()->role_id;
        if ($userId == 1) {
            $userData = [];
            $users = User::select('id', 'username', 'email', 'email_verified_at', 'created_at', 'deleted_at')->where('role_id', '!=', 1)->orderBy('created_at', 'desc')->withTrashed()->get();
            $userData = $users;
            foreach ($users as $key => $user) {
                if ($user->deleted_at != null) {
                    Arr::add($userData[$key], 'status', 'Removed');
                } else if ($user->email_verified_at != null) {
                    Arr::add($userData[$key], 'status', 'Verified');
                } else {
                    Arr::add($userData[$key], 'status', 'Unverified');
                }
            }
            return response()->json(['users' => $userData], 200);
        } else {
            return abort(401);
        }
    }

    public function userDelete(Request $request)
    {
        User::find($request->id)->delete();

        return response()->json('successfully user deleted', 200);
    }

    public function userActivate(Request $request)
    {
        User::withTrashed()->where('id', '=', $request->id)->update(['deleted_at' => null]);

        return response()->json('successfully user activate', 200);
    }

    public function userVerifiy(Request $request)
    {
        User::withTrashed()->where('id', '=', $request->id)->update(['email_verified_at' => now()]);

        return response()->json('successfully user verifiy', 200);
    }

    public function userDetail(Request $request)
    {
        $user = $request->id;
        $totalCount = [];

        $userName = User::where('id', '=', $user)->withTrashed()->value('username');
        $userFizzies = User::find($user)->fizzies()->get();

        $userReports = Report::where('user_id', '=', $user)->withTrashed()->get();

        $totalCount['userName'] = $userName;
        $totalCount['totalFizzies'] = $userFizzies->count();
        $totalCount['totalPlays'] = $userReports->count();
        $totalCount['totalPlayers'] = $userReports->sum('players');
        $totalCount['totalReoprts'] = (clone $userReports)->where('deleted_at', null)->count();
        $totalCount['hasImage'] = Storage::exists("/public/avatar/" . $user . ".jpg");

        return response()->json($totalCount, 200);
    }
}
