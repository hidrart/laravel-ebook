<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Book Index Page
                </div>
                @foreach ($books as $book)
                <a href="{{ url("/book") }}/{{ $book["id"] }}" class="">
                    <article class="px-10 py-5">
                        <h1 class="text-slate-900 font-semibold text-xl">{{ $book["title"] }}</h1>
                        <p class="text-slate-500 mt-3 text-justify">{{ $book["body"] }}</p>
                    </article>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>