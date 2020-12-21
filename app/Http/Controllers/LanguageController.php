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

    public function array_undot($dottedArray) {
        // print_r(count($dottedArray));exit;
        $array = array();
        foreach ($dottedArray as $key => $value) {
           
          array_set($array, $key, $value);
        }
        // print_r($array);
        // exit;
        return $array;
      }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        
        $limit = $request->has('limit') ? $request->limit : 10;
        $roles = Language::query();
            $roles = $roles->where('value_en', 'LIKE', "%{$request->value_en}%")
            ->where('parent', 'LIKE', "%{$request->parent}%")
            ->where('value_da', 'LIKE', "%{$request->value_da}%")
            ->paginate($limit);

        $siteData = [
            'roles' => $roles
        ];

        return response()->json($siteData);
    
    }

   public function add_to_database(){
    //    dd('entered');exit;
    $jsonStringen = file_get_contents(base_path('resources/assets/js/plugins/en.json'));
    $jsonStringda = file_get_contents(base_path('resources/assets/js/plugins/de.json'));
    // print_r($jsonString);
    $dataen = json_decode($jsonStringen, TRUE);
    $datada = json_decode($jsonStringda, TRUE);

   
    $arrayen = array_dot($dataen);
    $arrayda = array_dot($datada);
   

    foreach($arrayen as $key => $value)
        {
            $parent = explode(".",$key);

        $container = new Language([
            'parent' => $parent[0],
            'key_value' => $key,
            'value_en' => $value,
            'value_da' => $arrayda[$key]
        ]);

        $container->save();
        }
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

        // $id = decodeId($id);

        $role = Language::findOrFail($id);
        // dd($role);exit;
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

        $this->validate($request, [
            // 'name' => 'required|unique:roles,name,'.$id,
        ]);

        $requestData = $request->all();
        $role = Language::findOrFail($id);
        $role->update($requestData);

        //en.json file update
        $arr = [];
        $data1 = Language::select('key_value','value_en')->get();
        foreach($data1 as $val)
        {
            $arr[$val['key_value']] = $val['value_en'];
            
        }
        $data2 = $this->array_undot($arr);
        $dataen = json_encode($data2,JSON_PRETTY_PRINT);
      
        file_put_contents(base_path('resources/assets/js/plugins/en.json'), stripslashes($dataen));

        //de.json file update
       
        $arr = [];
        $data1 = Language::select('key_value','value_da')->get();
        foreach($data1 as $val)
        {
            $arr[$val['key_value']] = $val['value_da'];
            
        }
        $data2 = $this->array_undot($arr);
        $dataen = json_encode($data2,JSON_PRETTY_PRINT);
      
        file_put_contents(base_path('resources/assets/js/plugins/de.json'), stripslashes($dataen));

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
