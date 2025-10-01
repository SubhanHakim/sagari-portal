<x-app-layout>
    <div class="py-8 bg-gradient-to-br from-lime-50 via-green-50 to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 rounded-lg bg-lime-100 dark:bg-green-900 text-green-800 dark:text-lime-300 shadow">
                    <svg class="inline-block w-5 h-5 mr-2 text-lime-600 dark:text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white/80 dark:bg-gray-900/80 shadow-2xl rounded-3xl backdrop-blur-md border border-gray-200 dark:border-gray-800 p-8 space-y-8">
                <div class="flex flex-col items-center mb-6">
                    <img src="{{ asset('images/3.png') }}" class="w-32 drop-shadow-lg mb-4" alt="Sagari Logo" />
                    <h2 class="text-2xl font-bold text-green-700 dark:text-lime-300 mb-1">Buat User Baru</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-center text-sm">
                        Formulir pembuatan user untuk admin. Isi data user di bawah ini.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="dark:text-gray-200 font-semibold" />
                        <x-text-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg
                                   focus:ring-2 focus:ring-[#AEFD4C] dark:focus:ring-[#AEFD4C] focus:border-[#AEFD4C]
                                   hover:border-[#AEFD4C] dark:hover:border-[#AEFD4C] transition"
                            :value="old('name')"
                            required
                            autofocus
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="dark:text-gray-200 font-semibold" />
                        <x-text-input
                            id="email"
                            name="email"
                            type="email"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg
                                   focus:ring-2 focus:ring-[#AEFD4C] dark:focus:ring-[#AEFD4C] focus:border-[#AEFD4C]
                                   hover:border-[#AEFD4C] dark:hover:border-[#AEFD4C] transition"
                            :value="old('email')"
                            required
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- ROLE --}}
                    <div x-data="{
                            open:false,
                            selected: null,
                            items: @js($roles->map(fn($r)=>['id'=>$r->id,'name'=>$r->name]))
                        }" class="relative">
                        <x-input-label for="role" :value="__('Role')" class="dark:text-gray-200 font-semibold" />

                        <!-- Button -->
                        <button type="button"
                                @click="open=!open"
                                class="mt-1 w-full flex justify-between items-center rounded-lg border border-gray-300 dark:border-gray-700
                                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 transition
                                       focus:outline-none focus:ring-2 focus:ring-[#AEFD4C] focus:border-[#AEFD4C]">
                            <span x-text="selected ? selected.name : 'Pilih Role'" class="truncate"></span>
                            <svg class="w-4 h-4 opacity-70" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M5.25 7.5L10 12.25 14.75 7.5"/>
                            </svg>
                        </button>

                        <!-- Hidden input -->
                        <input type="hidden" name="role" x-ref="hidden" value="{{ old('role') }}">

                        <!-- Options -->
                        <div x-show="open" @click.outside="open=false" x-transition
                             class="absolute z-50 mt-1 w-full rounded-lg border border-gray-200 dark:border-gray-700
                                    bg-white dark:bg-gray-900 shadow-xl overflow-hidden">
                            <template x-for="item in items" :key="item.id">
                                <div @click="selected=item; $refs.hidden.value=item.id; open=false"
                                     class="flex justify-between items-center px-3 py-2 cursor-pointer 
                                            text-gray-900 dark:text-gray-100 hover:bg-[#AEFD4C]/30">
                                    <span x-text="item.name" class="truncate"></span>
                                    <svg x-show="selected && selected.id === item.id" class="w-4 h-4 text-[#AEFD4C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    {{-- POSITION --}}
                    <div x-data="{
                            open:false,
                            selected: null,
                            items: @js($positions->map(fn($p)=>['id'=>$p->id,'name'=>$p->name]))
                        }" class="relative">
                        <x-input-label for="position" :value="__('Position')" class="dark:text-gray-200 font-semibold" />

                        <!-- Button -->
                        <button type="button"
                                @click="open=!open"
                                class="mt-1 w-full flex justify-between items-center rounded-lg border border-gray-300 dark:border-gray-700
                                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 transition
                                       focus:outline-none focus:ring-2 focus:ring-[#AEFD4C] focus:border-[#AEFD4C]">
                            <span x-text="selected ? selected.name : 'Pilih Position'" class="truncate"></span>
                            <svg class="w-4 h-4 opacity-70" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M5.25 7.5L10 12.25 14.75 7.5"/>
                            </svg>
                        </button>

                        <!-- Hidden input -->
                        <input type="hidden" name="position" x-ref="hidden" value="{{ old('position') }}">

                        <!-- Options -->
                        <div x-show="open" @click.outside="open=false" x-transition
                             class="absolute z-50 mt-1 w-full rounded-lg border border-gray-200 dark:border-gray-700
                                    bg-white dark:bg-gray-900 shadow-xl overflow-hidden">
                            <template x-for="item in items" :key="item.id">
                                <div @click="selected=item; $refs.hidden.value=item.id; open=false"
                                     class="flex justify-between items-center px-3 py-2 cursor-pointer 
                                            text-gray-900 dark:text-gray-100 hover:bg-[#AEFD4C]/30">
                                    <span x-text="item.name" class="truncate"></span>
                                    <svg x-show="selected && selected.id === item.id" class="w-4 h-4 text-[#AEFD4C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button
                            class="px-6 py-2 text-base rounded-lg shadow bg-[#AEFD4C] hover:bg-[#9BE23F]
                                   text-gray-900 font-semibold transition transform hover:scale-105">
                            {{ __('Create') }}
                        </x-primary-button>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Password akan dikirim via link reset ke email user.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
