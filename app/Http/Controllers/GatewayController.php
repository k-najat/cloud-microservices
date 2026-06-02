<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    private string $articleService;
    private string $userService;

    public function __construct()
    {
        $this->articleService = env('ARTICLE_SERVICE_URL', 'http://localhost:8001');
        $this->userService    = env('USER_SERVICE_URL',    'http://localhost:8002');
    }

    // ─── Articles ────────────────────────────────────────────────

    public function articles(Request $request): JsonResponse
    {
        $response = Http::get("{$this->articleService}/api/articles");
        return response()->json($response->json(), $response->status());
    }

    public function createArticle(Request $request): JsonResponse
    {
        $response = Http::post("{$this->articleService}/api/articles", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function getArticle(string $id): JsonResponse
    {
        $response = Http::get("{$this->articleService}/api/articles/{$id}");
        return response()->json($response->json(), $response->status());
    }

    public function updateArticle(Request $request, string $id): JsonResponse
    {
        $response = Http::put("{$this->articleService}/api/articles/{$id}", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function deleteArticle(string $id): JsonResponse
    {
        $response = Http::delete("{$this->articleService}/api/articles/{$id}");
        return response()->json($response->json(), $response->status());
    }

    // ─── Users ───────────────────────────────────────────────────

    public function users(): JsonResponse
    {
        $response = Http::get("{$this->userService}/api/users");
        return response()->json($response->json(), $response->status());
    }

    public function createUser(Request $request): JsonResponse
    {
        $response = Http::post("{$this->userService}/api/users", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function getUser(string $id): JsonResponse
    {
        $response = Http::get("{$this->userService}/api/users/{$id}");
        return response()->json($response->json(), $response->status());
    }

    public function userNotifications(string $id): JsonResponse
    {
        $response = Http::get("{$this->userService}/api/users/{$id}/notifications");
        return response()->json($response->json(), $response->status());
    }

    // ─── Health ──────────────────────────────────────────────────

    public function health(): JsonResponse
    {
        $articleHealth = rescue(fn() => Http::timeout(3)->get("{$this->articleService}/api/health")->json(), ['status' => 'unreachable']);
        $userHealth    = rescue(fn() => Http::timeout(3)->get("{$this->userService}/api/health")->json(),    ['status' => 'unreachable']);

        return response()->json([
            'gateway'         => 'ok',
            'article-service' => $articleHealth,
            'user-service'    => $userHealth,
        ]);
    }
}
