<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view permission', ['only' => ['index']]);
        // $this->middleware('permission:create permission', ['only' => ['create','store']]);
        // $this->middleware('permission:update permission', ['only' => ['update','edit']]);
        // $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::paginate(10);

       return response()->json($permissions);
    }

    public function create()
    {
        return view('role-permission.permission.create');
    }

    public function store(Request $request)
    {
        try {

        $request->validate([
            'permissionName' => [
                'required',
                'string',
                'unique:permissions,name'
            ],
            'parentCategory'=>'required',

        ]);
        Permission::create([
            'name' => $request->permissionName,
            'parent_category'=>$request->parentCategory,
            'category'=>$request->category,
        ]);

        $response=[
            'status'=>200,
            'message'=>'Permissions has been created successfully',
        ];
       return response()->json($response);

                } catch (\Throwable $th) {
                    $response=[
                        'status'=>500,
                        'message'=>$th->getMessage()
                    ];
                    return response()->json($response);
                }
    }

    public function edit($id)
    {
        try {

            $data=Permission::findOrFail($id);

            $response=[
                'status'=>200,
                'data'=>$data,
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

    public function update(Request $request)
    {
        try {

        $request->validate([
            'permissionName' => [
                'required',
                'string',
            ],
            'parentCategory'=>'required',

        ]);
        $permission_date=Permission::findOrFail($request->id);
        $permission_date->update([
            'name' => $request->permissionName,
            'parent_category'=>$request->parentCategory,
            'category'=>$request->category,
        ]);

        $response=[
            'status'=>200,
            'message'=>'Permissions has been created successfully',
        ];
       return response()->json($response);

                } catch (\Throwable $th) {
                    $response=[
                        'status'=>500,
                        'message'=>$th->getMessage()
                    ];
                    return response()->json($response);
                }
    }

    public function destroy($permissionId)
    {
        try {

        Permission::where('id',$permissionId)->delete();
        $response=[
            'status'=>200,
            'message'=>'Permission has been deleted successfully',
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
}
