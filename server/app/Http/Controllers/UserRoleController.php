<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleController extends Controller
{
    /**
     * Get all users with their roles and permissions
     */
    public function getAllUsers(Request $request)
    {
        $users = User::with(['roles', 'permissions'])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $users,
            'message' => 'Users fetched successfully'
        ]);
    }

    /**
     * Get all available roles
     */
    public function getAllRoles()
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'data' => $roles,
            'message' => 'Roles fetched successfully'
        ]);
    }

    /**
     * Get all available permissions
     */
    public function getAllPermissions()
    {
        $permissions = Permission::all();

        return response()->json([
            'data' => $permissions,
            'message' => 'Permissions fetched successfully'
        ]);
    }

    /**
     * Assign roles to a user
     */
    public function assignRolesToUser(Request $request, $user_id)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $user = User::findOrFail($user_id);

        // Sync roles (removes old roles and assigns new ones)
        $user->syncRoles($request->roles);

        return response()->json([
            'data' => $user->load(['roles', 'permissions']),
            'message' => 'Roles assigned successfully'
        ]);
    }

    /**
     * Assign permissions to a user
     */
    public function assignPermissionsToUser(Request $request, $user_id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $user = User::findOrFail($user_id);

        // Sync permissions
        $user->syncPermissions($request->permissions);

        return response()->json([
            'data' => $user->load(['roles', 'permissions']),
            'message' => 'Permissions assigned successfully'
        ]);
    }

    /**
     * Remove role from user
     */
    public function removeRoleFromUser(Request $request, $user_id)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::findOrFail($user_id);
        $user->removeRole($request->role);

        return response()->json([
            'data' => $user->load(['roles', 'permissions']),
            'message' => 'Role removed successfully'
        ]);
    }

    /**
     * Remove permission from user
     */
    public function removePermissionFromUser(Request $request, $user_id)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name'
        ]);

        $user = User::findOrFail($user_id);
        $user->revokePermissionTo($request->permission);

        return response()->json([
            'data' => $user->load(['roles', 'permissions']),
            'message' => 'Permission removed successfully'
        ]);
    }

    /**
     * Get single user with roles and permissions
     */
    public function getSingleUser($user_id)
    {
        $user = User::with(['roles', 'permissions'])->findOrFail($user_id);

        return response()->json([
            'data' => $user,
            'message' => 'User fetched successfully'
        ]);
    }
}
