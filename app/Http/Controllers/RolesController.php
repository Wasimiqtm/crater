<?php
namespace Crater\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Crater\Conversation;
use Crater\Group;
use Crater\Http\Requests;
use Crater\Notifications\CustomerAdded;
use Crater\User;
use Illuminate\Support\Facades\Hash;
use Crater\Currency;
use Crater\CompanySetting;
use Crater\Address;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller{

    public function __construct()
    {
        /*$this->middleware('permission:view roles', ['only' => ['index']]);
        $this->middleware('permission:add roles', ['only' => ['create','store']]);
        $this->middleware('permission:edit roles', ['only' => ['edit','update']]);
        $this->middleware('permission:delete roles', ['only' => ['destroy']]);
        $this->middleware('permission:view role permission', ['only' => ['getRolePermissions']]);
        $this->middleware('permission:edit role permission', ['only' => ['updateRolePermission']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $roles = Role::query();
            $roles = $roles->where('name', 'LIKE', "%{$request->name}%")
            ->paginate($limit);

        $siteData = [
            'roles' => $roles
        ];

        return response()->json($siteData);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        $role = Role::find($role->id);

        return response()->json([
            'role' => $role,
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return response()->json([
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        //$role->guard_name = 'admin';
        $role->save();

        $role = Role::find($role->id);

        return response()->json([
            'role' => $role,
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if($role){
            $role->delete();
            return response()->json([
                'success' => true
            ]);
        }
    }

    /**
     * Remove the selected resources from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        foreach ($request->id as $id) {
            $role = Role::find($id);
            if($role){
                $role->delete();
            }
        }

        return response()->json([
            'success' => true
        ]);
    }


    /**
     * Get all permissions.
     *
     * @param  int  $role_id
     *
     * @return \Illuminate\Http\Response
     */
    public function getRolePermissions($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();

        $permissionss = $permissions->map(function ($item) use ($role) {
            $item->assigned = $role->hasPermissionTo($item->name);
            return $item;
        });

        $data = [
            'role' => $role,
            'permissions' => $permissionss
        ];

        return response()->json($data);
    }


    public function updateRolePermission($id, Request $request)
    {
        $role = Role::find($id);
        $assigned_permissions = $request->assigned_permissions;

        DB::table('role_has_permissions')->where('role_id', $id)->delete();
        $role->syncPermissions($assigned_permissions);

        return response()->json([
            'success' => true
        ]);
    }
}
