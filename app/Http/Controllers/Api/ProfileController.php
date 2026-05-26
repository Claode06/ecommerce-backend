<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $user = auth()->user()->load('userPoint');

        return response()->json(new UserResource($user));
    }

    public function update(Request $request): JsonResponse
    {
        $user = auth()->user();

        $validated = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'phone' => 'sometimes|string|max:15',
            'email' => 'sometimes|email|max:100|unique:users,email,'.$user->id,
        ])->validate();

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => new UserResource($user->fresh('userPoint')),
        ]);
    }
}
