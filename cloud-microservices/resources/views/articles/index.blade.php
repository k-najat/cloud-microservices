@extends('layouts.app')

@section('title', 'Tous les articles')

@section('content')

    {{-- Hero header --}}
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Articles</h1>
        <p class="text-gray-500 text-lg">Découvrez tous nos articles publiés sur le cloud.</p>
    </div>

    @if ($articles->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Aucun article pour l'instant</h2>
            <p class="text-gray-400 mb-6">Commencez par créer votre premier article.</p>
            <a href="{{ route('articles.create') }}"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Créer un article
            </a>
        </div>
    @else
        {{-- Articles grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach ($articles as $article)
                <article class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col">
                    {{-- Card color bar --}}
                    <div class="h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>

                    <div class="p-6 flex flex-col flex-1">
                        {{-- Category badge --}}
                        <div class="mb-3">
                            <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ $article->category }}
                            </span>
                            @if (!$article->published)
                                <span class="inline-block bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full ml-1">
                                    Brouillon
                                </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h2 class="text-lg font-bold text-gray-900 mb-2 leading-snug">
                            <a href="{{ route('articles.show', $article->_id) }}"
                                class="hover:text-indigo-600 transition-colors">
                                {{ $article->title }}
                            </a>
                        </h2>

                        {{-- Description --}}
                        <p class="text-gray-500 text-sm leading-relaxed flex-1 mb-4">
                            {{ $article->excerpt }}
                        </p>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <span class="text-xs text-gray-400">
                                {{ $article->created_at->diffForHumans() }}
                            </span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('articles.show', $article->_id) }}"
                                    class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                    Lire →
                                </a>
                                <a href="{{ route('articles.edit', $article->_id) }}"
                                    class="text-xs font-medium text-gray-400 hover:text-gray-600 transition-colors">
                                    Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $articles->links() }}
        </div>
    @endif

@endsection
