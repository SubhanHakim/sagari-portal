{{-- filepath: d:\project\client\Sagari\sagari-portal\resources\views\dashboard\users\index.blade.php --}}
<x-app-layout>
    <div class="py-8 bg-gradient-to-br from-indigo-100 via-blue-100 to-pink-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto space-y-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-4 text-indigo-900 dark:text-indigo-200">Daftar User</h1>

            <h1 class="text-white">
                {{ Auth::user()->getRoleNames()->join(', ') }}
            </h1>

            <!-- Statistik Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @php
                    $cardBase =
                        'relative overflow-hidden rounded-3xl border border-white/50 dark:border-white/10 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-xl transition-all hover:shadow-2xl hover:-translate-y-0.5';
                    $ringWrap =
                        'p-[1px] rounded-3xl bg-gradient-to-tr from-indigo-500/30 via-fuchsia-500/30 to-pink-500/30';
                    $inner = 'rounded-3xl p-6 bg-white/90 dark:bg-gray-950/60';
                @endphp

                <!-- Total User -->
                <div class="{{ $cardBase }}">
                    <div class="{{ $ringWrap }}">
                        <div class="{{ $inner }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 ring-1 ring-indigo-500/20">
                                        <!-- Users Icon -->
                                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            Total User</p>
                                        <p class="mt-1 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                                            {{ $users->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-indigo-500/10 blur-2xl"></div>
                        </div>
                    </div>
                </div>

                <!-- Total Position Dev -->
                <div class="{{ $cardBase }}">
                    <div class="{{ $ringWrap }}">
                        <div class="{{ $inner }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-pink-100 dark:bg-pink-900/40 ring-1 ring-pink-500/20">
                                        <!-- Code Icon -->
                                        <svg class="h-6 w-6 text-pink-600 dark:text-pink-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 18l6-6-6-6M8 6l-6 6 6 6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            Total Position Dev</p>
                                        <p class="mt-1 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                                            {{ $users->where('position.name', 'dev')->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="pointer-events-none absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-pink-500/10 blur-2xl"></div>
                        </div>
                    </div>
                </div>

                <!-- Total Role -->
                <div class="{{ $cardBase }}">
                    <div class="{{ $ringWrap }}">
                        <div class="{{ $inner }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 ring-1 ring-emerald-500/20">
                                        <!-- List Icon -->
                                        <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7H9M20 12H9M20 17H9M7 7H4v3h3V7Zm0 5H4v3h3v-3Zm0 5H4v3h3v-3Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            Total Role</p>
                                        <p class="mt-1 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                                            {{ $users->reduce(function($carry, $user) {
                                                return $carry->merge($user->getRoleNames());
                                            }, collect())->unique()->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="pointer-events-none absolute right-[-2rem] bottom-[-2rem] h-40 w-40 rounded-full bg-emerald-500/10 blur-2xl"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel User -->
            <div class="bg-white/80 dark:bg-gray-900/80 shadow-2xl rounded-3xl p-0 backdrop-blur-md border border-gray-200 dark:border-gray-800 w-full">
                <div class="overflow-x-auto rounded-3xl">
                    <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="sticky top-0 z-10 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md">
                            <tr class="border-b border-gray-200/60 dark:border-gray-800/60">
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                    Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                    Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                    Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                    Position</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100/70 dark:divide-gray-800/70">
                            @forelse ($users as $user)
                                @php
                                    $role = $user->getRoleNames()->implode(', ') ?: '-';
                                    $pos = $user->position->name ?? '-';
                                @endphp

                                <tr class="odd:bg-white even:bg-gray-50/60 dark:odd:bg-gray-900 dark:even:bg-gray-900/60 hover:bg-indigo-50/60 dark:hover:bg-gray-800/60 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-gray-100 leading-tight">
                                                    {{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-white font-semibold text-xs">
                                            {{ $role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-white font-semibold text-xs">
                                            {{ $pos }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Edit -->
                                            <a href="{{ route('dashboard.users.edit', $user) }}"
                                                class="inline-flex items-center px-2 py-1 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition"
                                                title="Edit">
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-300" fill="none"
                                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                </svg>
                                            </a>
                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('dashboard.users.destroy', $user) }}"
                                                onsubmit="return confirm('Yakin ingin hapus user ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-2 py-1 rounded hover:bg-rose-100 dark:hover:bg-rose-900/40 transition"
                                                    title="Delete">
                                                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-300" fill="none"
                                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-10 text-center text-gray-600 dark:text-gray-300">
                                        Belum ada data user.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>