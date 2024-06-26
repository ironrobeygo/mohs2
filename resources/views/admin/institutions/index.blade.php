<x-app-layout>
    <x-slot name="header">
        {{ __('Institutions') }}
        <a href="{{ route('institutions.add') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-mohs-green-600 border border-transparent rounded-lg active:mohs-green-600 hover:mohs-green-700 focus:outline-none">
            Add Institution
            <span class="ml-2" aria-hidden="true">+</span>
        </a>
    </x-slot>

    @livewire('institution.index')
</x-app-layout>
