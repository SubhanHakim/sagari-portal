<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.create', [
            'roles' => \App\Models\Role::all(),
            'positions' => \App\Models\Position::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'role'     => ['required', Rule::exists('roles', 'id')],
            'position' => ['required', Rule::exists('positions', 'id')],
        ]);

        $user = DB::transaction(function () use ($validated) {
            return User::create([
                'name'        => $validated['name'],
                'email'       => $validated['email'],
                'role_id'     => $validated['role'],
                'position_id' => $validated['position'],
                'password'    => Hash::make(Str::random(32)),
            ]);
        });

        // generate token & kirim reset setelah commit
        $token = Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);

        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', "User {$user->email} dibuat. Link reset password dikirim.");
    }
}
