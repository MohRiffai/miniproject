<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold mb-4">
                        <span class="text-xl">{{ __('Welcome back,') }} {{ Auth::user()->name }} </span>
                    </h2>
                    {{-- <p class="text-lg">
                        {{ __("Role:") }} {{ Auth::user()->roles->implode('name', ', ') }}
                    </p> --}}
                    <p class="text-lg">
                        @if (Auth::user()->roles->isEmpty())
                            {{ __('Role :') }} {{ __('Anda Belum Mendapatkan Role') }} 
                        @else
                             {{ __('Role :') }} {{ Auth::user()->roles->implode('name', ', ') }}
                        @endif
                    </p>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
