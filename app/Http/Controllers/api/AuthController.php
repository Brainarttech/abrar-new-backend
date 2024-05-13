<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()

    {
    // $this->middleware('permission:view user', ['only' => ['index']]);
    // $this->middleware('permission:create user', ['only' => ['create','store']]);
    // $this->middleware('permission:update user', ['only' => ['update','edit']]);
    // $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }

    public function login(Request $request)
    {

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $roles = $user->roles->pluck('name');
            $permissions = $user->roles->flatMap->permissions->pluck('name')->unique();
            $token = $user->createToken('AuthToken')->plainTextToken;

            $response = [
                'status' => 200,
                'message'=>"Logged In Successfully",
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $roles,
                    'permissions' => $permissions,
                ],
                'token' => $token,
            ];
            return response()->json($response);
        }
        else{
            $response = [
                'status' => 401,
                'message'=>" The provided credentials are incorrect.",
            ];
            return response()->json($response);
        }

    }


    public function index()
    {
        $users = User::get();
        return view('role-permission.user.index', ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('role-permission.user.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {

    try {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'roleData' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->roleData );


$response=[
'status'=>200,
'message'=>'User created successfully with roles',
];
return response()->json($response);

} catch (\Throwable $th) {
    $response=[
        'status'=>500,
        'message'=>$th->getMessage(),
        ];
    return response()->json($response);
}
    }

    public function roles(){
        $data=Role::all();
        $response=[
            'status'=>200,
            'message'=>"Roles have been retrived successfully",
            'data'=>$data,
        ];
    }


    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'roles' => 'required'
        ]);

        $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]);

        $user->syncRoles($request->roles);

        $token = $user->createToken('AuthToken')->plainTextToken;
        $response=[
            'status'=>200,
            'message'=>'User created successfully with roles',
            'token'=>$token
        ];
        return response()->json($response);
    }

    public function edit($id)
    {
        try {

        $data=User::with('roles')->findOrFail($id);

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
                'name' => 'required|string|max:255',
                'password' => 'nullable|string|min:8|max:20',
                'roleData' => 'required'
            ]);

        $user=User::findOrFail($request->userid);


        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if(!empty($request->password)){
            $data += [
                'password' => Hash::make($request->password),
            ];
        }

        $user->update($data);
        $user->syncRoles($request->roleData);
        $response=[
        'status'=>200,
        'message'=>'User Updated Successfully with roles',

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

    public function destroy($userId)
    {
        try {
        $user = User::findOrFail($userId);
        $user->delete();
        $response=[
            'status'=>200,
            'message'=>"User has been deleted successfully",
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

    public function users(){
        try {
            $data = User::with('roles')->paginate(11);

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
}
}
