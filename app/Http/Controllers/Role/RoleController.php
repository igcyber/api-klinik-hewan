<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $roles = Role::where('name', 'ilike', '%' . $search . '%')->orderBy('id','desc')->get();
        return response()->json([
                "roles" => $roles->map(function($role){
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'created_at' => $role->created_at->format('d-m-Y'),
                        'permissions' => $role->permissions,
                        'permissions_pluck' => $role->permissions->pluck('name')
                    ];
                })
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exist_role = Role::where("name", $request->name)->first();
        if($exist_role){
            return response()->json([
                "http_code" => 403,
                "message" => "Role already exists"
            ]);
        }
        $role = Role::create([
            "name" => $request->name,
            "guard_name" => "api"
        ]);
        foreach($request->permissions as $permission){
            $role->givePermissionTo($permission);
        }
        return response()->json([
            "http_code" => 200,
            "message" => "Role created successfully"
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exist_role = Role::where("id", "<>", $id)->where("name", $request->name)->first();
        if($exist_role){
            return response()->json([
                "http_code" => 403,
                "message" => "Role already exists"
            ]);
        }
        $role = Role::findOrFail($id);
        $role->update([
            "name" => $request->name,
        ]);
        $role->syncPermissions($request->permissions);
        return response()->json([
            "http_code" => 200,
            "message" => "Role updated successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json([
            "http_code" => 200,
            "message" => "Role deleted successfully"
        ]);
    }
}
