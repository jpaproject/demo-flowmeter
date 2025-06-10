<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();
            return view('roles-permissions.index', compact('roles', 'permissions'));
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Error loading roles and permissions: '.$e->getMessage());
            return redirect()->back()->withErrors('Failed to load roles and permissions.');
        }
    }

    public function storeRole(Request $request)
    {
        try {
            $request->validate(['name' => 'required|unique:roles,name']);
            Role::create(['name' => $request->name]);
            return redirect()->back()->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating role: '.$e->getMessage());
            return redirect()->back()->withErrors('Failed to create role.');
        }
    }

    public function updateRole(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);
            $role->name = $request->name;
            $role->save();
            return redirect()->back()->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating role ID ' . $id . ': '.$e->getMessage());
            return redirect()->back()->withErrors('Failed to update role.');
        }
    }

    public function destroyRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return redirect()->back()->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting role ID ' . $id . ': '.$e->getMessage());
            return redirect()->back()->withErrors('Failed to delete role.');
        }
    }

    public function storePermission(Request $request)
    {
        try {
            $request->validate(['name' => 'required|unique:permissions,name']);
            Permission::create(['name' => $request->name]);
            return redirect()->back()->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating permission: '.$e->getMessage());
            return redirect()->back()->withErrors('Failed to create permission.');
        }
    }

    public function updatePermission(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);
            $permission->name = $request->name;
            $permission->save();
            return redirect()->back()->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating permission ID ' . $id . ': '.$e->getMessage());
            return redirect()->back()->withErrors('Failed to update permission.');
        }
    }

    public function destroyPermission($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return redirect()->back()->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting permission ID ' . $id . ': '.$e->getMessage());
            return redirect()->back()->withErrors('Failed to delete permission.');
        }
    }

    public function assignPermissions(Request $request, $roleId)
    {
        try {
            $role = Role::findOrFail($roleId);

            $permissionIds = $request->permissions ?? [];

            // Ambil nama permission dari ID
            $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

            $role->syncPermissions($permissionNames);

            return redirect()->back()->with('success', 'Permissions updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error assigning permissions to role ID ' . $roleId . ': '.$e->getMessage());
            return redirect()->back()->withErrors('Failed to assign permissions.');
        }
    }
}
