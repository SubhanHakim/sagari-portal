<?php
// app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Position;
use App\Services\DualWriteUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(private DualWriteUser $dual) {}

    public function create()
    {
        return view('admin.users.create', [
            'roles' => Role::all(),
            'positions' => Position::all(),
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

        $existsOnDb2 = User::on('mysql2')->where('email', $validated['email'])->exists();
        if ($existsOnDb2) {
            return back()->withErrors(['email' => 'Email sudah ada di DB2.'])->withInput();
        }

        $user = $this->dual->create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'position_id' => $validated['position'],
        ]);

        // Assign role Spatie
        $role = Role::find($validated['role']);
        if ($role) {
            $user->assignRole($role->name);
        }

        $token = Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);

        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', "User {$user->email} dibuat di DB1 & DB2. Reset link dikirim.");
    }


    public function index()
    {
        $users = User::all();
        return view('dashboard.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $positions = Position::all();
        return view('dashboard.users.edit', compact('user', 'roles', 'permissions', 'positions'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'roles'    => ['array'],
            'roles.*'  => ['exists:roles,name'],
            'permissions'   => ['array'],
            'permissions.*' => ['exists:permissions,name'],
            'position' => ['required', 'exists:positions,id'],
        ]);

        $user->update([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'position_id' => $validated['position'],
        ]);

        // Sync roles & permissions (RBAC)
        $user->syncRoles($validated['roles'] ?? []);
        $user->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('dashboard.users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        // Cek permission (optional, karena sudah ada di route middleware)
        if (!auth()->user()->can('delete')) {
            abort(403, 'Tidak punya akses hapus user');
        }

        // Hapus user
        $user->delete();

        return redirect()->route('dashboard.users.index')
            ->with('success', 'User berhasil dihapus!');
    }   
}
