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
// use Spatie\Permission\Models\Role;
use Crater\Language;

class LanguageController extends Controller{

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
    public function index(Request $request){

        



            // Read File
        
            $jsonString = file_get_contents(base_path('resources/assets/js/plugins/en.json'));
        
            $data = json_decode($jsonString, true);
        
            // dd($data);

            return $data;
        
            // Update Key
        
            $data['country.title'] = "Change Manage Country";
        
        
        
            // Write File
        
            $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
        
            file_put_contents(base_path('resources/lang/en.json'), stripslashes($newJsonString));
        
        
        
            // Get Key Value
        
            dd(__('country.title'));
        
        

        // $limit = $request->has('limit') ? $request->limit : 10;

        // $roles = Language::paginate($limit);

        // $siteData = [
        //     'roles' => $roles
        // ];

        // return response()->json($siteData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){

        return view('admin.roles.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:roles,name',
        ]);

        $requestData = $request->all();
        $requestData['guard_name'] = 'admin';

        Role::create($requestData);

        Session::flash('success', 'Role added!');

        return redirect('admin/roles');
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

        $id = decodeId($id);

        $role = Role::findOrFail($id);

        return view('admin.roles.edit',compact('role'));
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

        $id = decodeId($id);

        $this->validate($request, [
            'name' => 'required|unique:roles,name,'.$id,
        ]);

        $requestData = $request->all();

        $role = Role::findOrFail($id);

        $role->update($requestData);

        Session::flash('success', 'Role updated!');

        return redirect('admin/roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Hashids::decode($id)[0];

        $role = Role::find($id);

        if($role){
            $role->delete();
            $response['message'] = 'Role deleted!';
            $status = $this->successStatus;
        }else{
            $response['message'] = 'Role not exist against this id!';
            $status = $this->errorStatus;
        }

        return response()->json(['result'=>$response], $status);

    }


    /**
     * Get all permissions.
     *
     * @param  int  $role_id
     *
     * @return \Illuminate\Http\Response
     */
    public function getRolePermissions($role_id){

        $id = decodeId($role_id);

        $role = Role::find($id);

        $permissions = Permission::all();

        return view('admin.roles.permissions',compact('role','permissions'));
    }


    public function updateRolePermission($role_id, Request $request){

        $id = decodeId($role_id);

        $permissions = $request->permissions;

        $role = Role::findById($id);

        DB::table('role_has_permissions')->where('role_id', $id)->delete();

        $role->syncPermissions($permissions);

        Session::flash('success', 'Permission updated!');

        return redirect('admin/roles');

    }
}
