<x-app-layout>
    <div
        class="py-8 bg-gradient-to-br from-indigo-100 via-blue-100 to-pink-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <adiv
                    class="mb-4 p-4 rounded-lg bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 shadow">
                    <svg class="inline-block w-5 h-5 mr-2 text-green-500 dark:text-green-300" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </adiv>
            @endif

            <div
                class="bg-white/80 dark:bg-gray-900/80 shadow-2xl rounded-3xl backdrop-blur-md border border-gray-200 dark:border-gray-800 p-8 space-y-8">
                <div class="flex flex-col items-center mb-6">
                    <img src="{{ asset('images/logo_sagari.svg') }}" alt="Sagari Logo"
                        class="w-16 h-16 mb-2 drop-shadow-lg" />
                    <h2 class="text-2xl font-bold text-indigo-900 dark:text-indigo-200 mb-1">Buat User Baru</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-center text-sm">Formulir pembuatan user untuk admin.
                        Isi data user di bawah ini.</p>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="dark:text-gray-200 font-semibold" />
                        <x-text-input id="name" name="name" type="text"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-600"
                            :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="dark:text-gray-200 font-semibold" />
                        <x-text-input id="email" name="email" type="email"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-600"
                            :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="role" :value="__('Role')" class="dark:text-gray-200 font-semibold" />
                        <select id="role" name="role"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-600 transition"
                            required>
                            <option value="" disabled selected>Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role') == $role->id)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="position" :value="__('Position')" class="dark:text-gray-200 font-semibold" />
                        <select id="position" name="position"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-600 transition"
                            required>
                            <option value="" disabled selected>Pilih Position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}" @selected(old('position') == $position->id)>{{ $position->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button
                            class="px-6 py-2 text-base rounded-lg shadow bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition">
                            {{ __('Create') }}
                        </x-primary-button>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Password akan dikirim via link reset ke
                            email user.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>