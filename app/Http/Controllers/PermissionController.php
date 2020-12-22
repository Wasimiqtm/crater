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

class PermissionController extends Controller{

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
        $permissions = Permission::query();
            $permissions = $permissions->where('name', 'LIKE', "%{$request->name}%")
            ->paginate($limit);

        $siteData = [
            'permissions' => $permissions
        ];

        return response()->json($siteData);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function abilities()
    {
        $role = Role::where('name', auth()->user()->role)->first();
        $permissions = Permission::all();

        $abilities = [];
        foreach($permissions as $item)
        {
            if($role->hasPermissionTo($item->name))
            {
                $abilities[] = $item->name;
            }
        }
        $data = [
            'abilities' => $abilities
        ];

        return response()->json($data);
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
        $permission = new Permission();
        $permission->name = $request->name;
        //$permission->guard_name = 'admin';
        $permission->save();
        $permission = Permission::find($permission->id);

        return response()->json([
            'permission' => $permission,
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
        $permission = Permission::findOrFail($id);

        return response()->json([
            'permission' => $permission
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
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->save();

        $permission = Permission::find($permission->id);

        return response()->json([
            'permission' => $permission,
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
        $permission = Permission::find($id);
        if($permission){
            $permission->delete();
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
        Permission::whereIn('id', $request->id)->delete();;

        return response()->json([
            'success' => true
        ]);
    }
}
