<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::select('_id', 'name', 'email', 'created_at')->get();
        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:mongodb.users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function show(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'sometimes|string|max:100',
            'email' => 'sometimes|email',
        ]);

        $user->update($validated);
        return response()->json($user);
    }

    public function notifications(string $id): JsonResponse
    {
        $notifications = Notification::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($notifications);
    }

    public function markRead(string $id, string $notifId): JsonResponse
    {
        $notif = Notification::where('user_id', $id)->findOrFail($notifId);
        $notif->update(['read' => true]);
        return response()->json(['message' => 'Marked as read']);
    }
}
