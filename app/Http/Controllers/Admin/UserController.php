<?php
// app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DualWriteUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function __construct(private DualWriteUser $dual) {}

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
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'], // cek di DB1
            'role'     => ['required', Rule::exists('roles','id')],
            'position' => ['required', Rule::exists('positions','id')],
        ]);

        // (opsional) Cegah duplikat di DB2 juga:
        $existsOnDb2 = \App\Models\User::on('mysql2')->where('email', $validated['email'])->exists();
        if ($existsOnDb2) {
            return back()->withErrors(['email' => 'Email sudah ada di DB2.'])->withInput();
        }

        // 1) dual write (all-or-nothing)
        $user = $this->dual->create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'role_id'     => $validated['role'],
            'position_id' => $validated['position'],
        ]);

        // 2) kirim reset password
        $token = Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);

        // 3) (opsional) verifikasi email
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', "User {$user->email} dibuat di DB1 & DB2. Reset link dikirim.");
    }

    
}
