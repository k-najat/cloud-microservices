<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\RabbitMQPublisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(): JsonResponse
    {
        $articles = Article::latest()->paginate(10);
        return response()->json($articles);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'content'     => 'required|string',
            'category'    => 'required|string|max:100',
            'published'   => 'boolean',
            'user_id'     => 'required|string',
            'author_name' => 'required|string|max:100',
        ]);

        $validated['published'] = $request->boolean('published');

        $article = Article::create($validated);

        // Publish event to RabbitMQ
        try {
            $publisher = new RabbitMQPublisher();
            $publisher->publish('article.created', [
                'event'       => 'article.created',
                'article_id'  => (string) $article->_id,
                'title'       => $article->title,
                'author_name' => $article->author_name,
                'user_id'     => $article->user_id,
                'published'   => $article->published,
                'timestamp'   => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            // Log but don't fail — RabbitMQ optional
            logger()->warning('RabbitMQ publish failed: ' . $e->getMessage());
        }

        return response()->json($article, 201);
    }

    public function show(string $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:500',
            'content'     => 'sometimes|string',
            'category'    => 'sometimes|string|max:100',
            'published'   => 'boolean',
        ]);

        if (isset($validated['published'])) {
            $validated['published'] = $request->boolean('published');
        }

        $article->update($validated);

        // Publish update event
        try {
            $publisher = new RabbitMQPublisher();
            $publisher->publish('article.updated', [
                'event'      => 'article.updated',
                'article_id' => (string) $article->_id,
                'title'      => $article->title,
                'user_id'    => $article->user_id,
                'timestamp'  => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            logger()->warning('RabbitMQ publish failed: ' . $e->getMessage());
        }

        return response()->json($article);
    }

    public function destroy(string $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['message' => 'Article deleted successfully']);
    }

    public function byUser(string $userId): JsonResponse
    {
        $articles = Article::where('user_id', $userId)->latest()->get();
        return response()->json($articles);
    }
}
