<x-app-layout>
    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Edit User: {{ $user->name }}</h2>
        <form method="POST" action="{{ route('dashboard.users.update', $user) }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Position</label>
                <select name="position" class="w-full border rounded p-2" required>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ $user->position_id == $position->id ? 'selected' : '' }}>
                            {{ $position->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Role</label>
                @foreach($roles as $role)
                    <label class="flex items-center mb-1">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                            {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $role->name }}</span>
                    </label>
                @endforeach
            </div>
            <div class="mb-4">
                <label class="block mb-1">Permission</label>
                @foreach($permissions as $permission)
                    <label class="flex items-center mb-1">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</x-app-layout>