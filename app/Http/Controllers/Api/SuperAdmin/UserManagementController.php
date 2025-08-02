<?php
namespace App\Http\Controllers\Api\SuperAdmin;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserDetailResource;

class UserManagementController extends Controller
{
    public function index()
    {
        // Exclude the super admin from the list
        $users = User::where('role_id', '!=', 1)->with('role')->latest()->get();
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        // You can't view the super-admin's profile details this way
        if ($user->hasRole('super-admin')) {
            return response()->json(['message' => 'Action not allowed.'], 403);
        }
        return new UserDetailResource($user->load('notes', 'role'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->hasRole('super-admin')) {
             return response()->json(['message' => 'Cannot change status of Super Admin.'], 403);
        }
        $user->is_active = !$user->is_active;
        $user->save();
        return response()->json([
            'message' => 'User status updated successfully.',
            'user' => new UserResource($user)
        ]);
    }
}