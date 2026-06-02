@extends('layouts.app')

@section('title', $article->title)

@section('content')

    {{-- Back + actions --}}
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('articles.show', $article->_id) }}"
            class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Retour aux articles
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('articles.edit', $article->_id) }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-indigo-600 border border-gray-200 hover:border-indigo-300 px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier
            </a>
            <form action="{{ route('articles.destroy', $article->_id) }}" method="POST"
                onsubmit="return confirm('Supprimer cet article ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 text-sm font-medium text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 px-3 py-1.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    {{-- Article --}}
    <article class="max-w-3xl">

        {{-- Meta --}}
        <div class="flex items-center gap-3 mb-4">
            <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                {{ $article->category }}
            </span>
            @if (!$article->published)
                <span class="inline-block bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                    Brouillon
                </span>
            @else
                <span class="inline-block bg-green-50 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                    Publié
                </span>
            @endif
            <span class="text-xs text-gray-400">{{ $article->created_at->format('d M Y') }}</span>
        </div>

        {{-- Title --}}
        <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-4">
            {{ $article->title }}
        </h1>

        {{-- Description --}}
        <p class="text-xl text-gray-500 leading-relaxed mb-8 pb-8 border-b border-gray-200">
            {{ $article->description }}
        </p>

        {{-- Content --}}
        <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap text-base">
            {{ $article->content }}
        </div>

        {{-- Footer --}}
        <div class="mt-12 pt-6 border-t border-gray-200 flex items-center justify-between">
            <span class="text-sm text-gray-400">
                Dernière mise à jour : {{ $article->updated_at->diffForHumans() }}
            </span>
            <a href="{{ route('articles.index') }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                ← Tous les articles
            </a>
        </div>

    </article>

@endsection
