<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(9);
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'content'     => 'required|string',
            'category'    => 'required|string|max:100',
            'published'   => 'boolean',
            'author_name' => 'nullable|string|max:100',
        ]);

        $validated['slug']        = Str::slug($validated['title']);
        $validated['published']   = $request->boolean('published');
        $validated['author_name'] = $validated['author_name'] ?? 'Anonyme';

        Article::create($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Article ajouté avec succès !');
    }

    public function show(string $id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', compact('article'));
    }

    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'content'     => 'required|string',
            'category'    => 'required|string|max:100',
            'published'   => 'boolean',
        ]);

        $validated['published'] = $request->boolean('published');

        $article->update($validated);

        return redirect()->route('articles.show', $article->_id)
            ->with('success', 'Article mis à jour avec succès !');
    }

    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article supprimé avec succès !');
    }
}
