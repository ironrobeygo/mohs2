<x-app-layout>
    <x-slot name="header">
        {{ __('Courses') }}
        @role('admin')
        <a href="{{ route('courses.add') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-mohs-green-600 border border-transparent rounded-lg active:mohs-green-600 hover:mohs-green-700 focus:outline-none">
            Add Course
            <span class="ml-2" aria-hidden="true">+</span>
        </a>
        @endrole
    </x-slot>

    @livewire('courses.list-courses')
</x-app-layout>
