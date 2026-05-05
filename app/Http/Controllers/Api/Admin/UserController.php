<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::with('role:role_id,name')->orderBy('name')->get(['id', 'name', 'email', 'role_id', 'is_active', 'created_at']));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8',
            'role_id'   => 'required|exists:roles,role_id',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            ...$request->only(['name', 'email', 'role_id', 'is_active']),
            'password' => $request->password,
        ]);

        return response()->json($user->load('role'), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(User::with('role')->findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'sometimes|string|max:100',
            'email'     => "sometimes|email|unique:users,email,{$id}",
            'password'  => 'nullable|string|min:8',
            'role_id'   => 'sometimes|exists:roles,role_id',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'email', 'role_id', 'is_active']);
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return response()->json($user->load('role'));
    }

    public function destroy(int $id): JsonResponse
    {
        if ($id === request()->user()->id) {
            return response()->json(['message' => 'Tidak dapat menghapus akun sendiri.'], 403);
        }

        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User dihapus.']);
    }

    public function roles(): JsonResponse
    {
        return response()->json(Role::all());
    }
}
