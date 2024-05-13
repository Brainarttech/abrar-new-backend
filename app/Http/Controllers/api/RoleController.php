<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;
class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view role', ['only' => ['index']]);
        // $this->middleware('permission:create role', ['only' => ['create','store','addPermissionToRole','givePermissionToRole']]);
        // $this->middleware('permission:update role', ['only' => ['update','edit']]);
        // $this->middleware('permission:delete role', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::get();
        $response=[
            'status'=>200,
            'message'=>'Roles has been retrived successfully',
            'data'=>$roles,
        ];
       return response()->json($response);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        try {

        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name'
            ]
        ]);

        Role::create([
            'name' => $request->name
        ]);
        $response=[
            'status'=>200,
            'message'=>'Role Created Successfully',
        ];
        return response()->json($response);
          //code...
        } catch (\Throwable $th) {
            $response=[
                'status'=>500,
                'message'=>$th->getMessage(),
            ];
        }
    }

    public function edit($id)
    {
        try {
            $data = Role::findOrFail($id);
            $response=[
                'status'=>200,
                'message'=>'Roles has been retrived successfully',
                'data'=>$data,
            ];
           return response()->json($response);
        }
        catch (\Throwable $th) {

            $reponse=[
                'status'=>500,
                'message'=>$th->getMessage(),
                'data'=>null,
            ];
           return response()->json($reponse);
        }
    }

    public function update(Request $request)
    {
        try {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name,'.$request->id
            ]
        ]);
        $role=Role::find($request->id);
        $role->update([
            'name' => $request->name
        ]);

        $response=[
            'status'=>200,
            'message'=>'Role Updated Successfully',
        ];
        return response()->json($response);

          //code...
        } catch (\Throwable $th) {

        $response=[
            'status'=>500,
            'message'=>$th->getMessage(),
        ];
        return response()->json($response);

        }
    }

    public function destroy($id)
    {
        try {

        $role = Role::where('id',$id)->delete();
        $response=[
            'status'=>200,
            'message'=>'Role Deleted Successfully',
        ];
        return response()->json($response);
                    //code...
                }
                catch (\Throwable $th) {
                    $response=[
                        'status'=>500,
                        'message'=>$th->getMessage(),
                    ];
                    return response()->json($response);
                }
    }

    public function addPermissionToRole($roleId)
    {
        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);
        $rolePermissions = DB::table('role_has_permissions')
                                ->where('role_has_permissions.role_id', $role->id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();

        return view('role-permission.role.add-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);
        $response=[
            'status'=>200,
            'message'=>'Permissions added to role',
        ];
        return response()->json($response);
    }
}

