<?php
// app/Http/Controllers/Auth/NewPasswordController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class NewPasswordController extends Controller
{
    // GET /reset-password/{token}
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    // POST /reset-password
    public function store(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required','email'],
            'password' => ['required','confirmed','min:8'],
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user) use ($request) {
                $hash     = Hash::make($request->password);
                $remember = Str::random(60);
                $now      = now();

                DB::connection('mysql')->beginTransaction();
                DB::connection('mysql2')->beginTransaction();

                try {
                    // builder helper: WHERE email = ? OR global_id = ? (jika global_id ada)
                    $applyWhere = function ($qb) use ($user) {
                        $qb->where('email', $user->email);
                        if (!empty($user->global_id)) {
                            $qb->orWhere('global_id', $user->global_id);
                        }
                        return $qb;
                    };

                    $rows1 = $applyWhere(DB::connection('mysql')->table('users'))
                        ->update([
                            'password'       => $hash,
                            'remember_token' => $remember,
                            'updated_at'     => $now,
                        ]);

                    $rows2 = $applyWhere(DB::connection('mysql2')->table('users'))
                        ->update([
                            'password'       => $hash,
                            'remember_token' => $remember,
                            'updated_at'     => $now,
                        ]);

                    // WAJIB: pastikan dua-duanya ter-update
                    if ($rows1 < 1 || $rows2 < 1) {
                        throw new \RuntimeException(
                            "Password reset didn't affect both DBs (DB1: {$rows1}, DB2: {$rows2})."
                        );
                    }

                    DB::connection('mysql')->commit();
                    DB::connection('mysql2')->commit();

                    // refresh instance dari DB1 & fire event standar
                    $user->setConnection('mysql')->refresh();
                    event(new PasswordReset($user));

                } catch (\Throwable $e) {
                    DB::connection('mysql')->rollBack();
                    DB::connection('mysql2')->rollBack();
                    throw $e;
                }
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
