@extends('layouts.app')

@section('title', 'Nouvel article')

@section('content')

    {{-- Page header --}}
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('articles.index') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Nouvel article</h1>
            <p class="text-gray-500 text-sm mt-0.5">Remplissez les informations ci-dessous</p>
        </div>
    </div>

    <div class="max-w-3xl">
        <form action="{{ route('articles.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Titre de l'article..."
                    class="w-full px-4 py-2.5 rounded-lg border @error('title') border-red-400 bg-red-50 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 placeholder-gray-400 transition"
                >
                @error('title')
                    <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select
                    id="category"
                    name="category"
                    class="w-full px-4 py-2.5 rounded-lg border @error('category') border-red-400 bg-red-50 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 transition bg-white"
                >
                    <option value="">Choisir une catégorie...</option>
                    @foreach (['Cloud', 'DevOps', 'Sécurité', 'Infrastructure', 'Développement', 'Tutoriel', 'General'] as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Description courte <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Un résumé accrocheur de l'article (max 500 caractères)..."
                    class="w-full px-4 py-2.5 rounded-lg border @error('description') border-red-400 bg-red-50 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 placeholder-gray-400 transition resize-none"
                    maxlength="500"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Content --}}
            <div>
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Contenu <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="content"
                    name="content"
                    rows="12"
                    placeholder="Rédigez le contenu complet de l'article ici..."
                    class="w-full px-4 py-2.5 rounded-lg border @error('content') border-red-400 bg-red-50 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 placeholder-gray-400 transition resize-y font-mono text-sm"
                >{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Published toggle --}}
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <input
                    type="checkbox"
                    id="published"
                    name="published"
                    value="1"
                    {{ old('published') ? 'checked' : '' }}
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                >
                <div>
                    <label for="published" class="text-sm font-semibold text-gray-700 cursor-pointer">
                        Publier immédiatement
                    </label>
                    <p class="text-xs text-gray-400 mt-0.5">Si décoché, l'article sera sauvegardé comme brouillon</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Enregistrer l'article
                </button>
                <a href="{{ route('articles.index') }}"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-100 transition-colors">
                    Annuler
                </a>
            </div>

        </form>
    </div>

@endsection
