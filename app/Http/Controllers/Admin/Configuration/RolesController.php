<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

use Yajra\DataTables\Facades\DataTables;

use App\Http\Requests\Configuration\Role\AddRoleRequest;
use App\Http\Requests\Configuration\Role\UpdateRolePermissionRequest;
use App\Http\Requests\Configuration\Role\UpdateRoleRequest;

use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    public function index()
    {
        return view('contents.configurations.roles.index');
    }

    public function table()
    {
        $roles = Role::select('id', 'name');

        return DataTables::of($roles)
        ->addColumn('actions', function($row){
            $btn = '';

            if(Auth::user()->can('view-note'))
                $btn = $btn . '<a class="mx-1 btn btn-secondary" href="' . route('role.show', ['role'=>$row->id]) . '">View</a>';
            if(Auth::user()->can('update-role'))
                $btn = $btn . '<a class="mx-1 btn btn-primary" href="' . route('role.edit', ['role'=>$row->id]) . '">Edit</a>';
            if(Auth::user()->can('delete-role'))
                $btn = $btn . '
                    <form method="POST" action="' . route('role.destroy', ['role'=>$row->id]) . '" onsubmit="return confirm(\'Are you sure?\')">
                        '.csrf_field().'
                        '. method_field('DELETE') .'
                        <button type="submit" class="mx-1 btn btn-danger">Delete</button>
                    </form>
                ';

            return '<div class="d-flex">'.$btn.'</div>';
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
    
    public function create()
    {
        return view('contents.configurations.roles.create');
    }
    
    public function store(AddRoleRequest $request)
    {
        try {
            
            $role = Role::create([
                'name'=>$request->name
            ]);

            return redirect()->route('role.edit', ['role'=>$role])->with('success', 'Role created. You can now add permissions to this role.');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    public function show(Role $role)
    {
        return view('contents.configurations.roles.view', compact('role'));
    }
    
    public function edit(Role $role)
    {
        return view('contents.configurations.roles.edit', compact('role'));
    }
    
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            
            $role->update([
                'name'=>$request->name
            ]);

            return redirect()->route('role.index')->with('success', 'Role updated');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    public function destroy(Role $role)
    {
        try {
            $users = User::whereHas("roles", function($q) use($role){ 
                $q->where("id", $role->id); 
            })->get();
            
            if(count($users) > 0) return redirect()->back()->with('error', 'Role cannot be deleted. There are still users assigned to this role.');
            
            $role->delete();

            return redirect()->back()->with('success', 'Role deleted');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function tableRolePermission(Role $role, $group)
    {
        try {
            
            $selected = "";

            if($group == 'roles') $selected = 'role';
            else if($group == 'users') $selected = 'user';
            else if($group == 'notes') $selected = 'note';

            $role_permissions = Role::with(['permissions'=>function($q) use($selected){
                $q->where('group', $selected);
                $q->select('id');
            }])
            ->where('name', $role->name)
            ->select('id')
            ->first();

            $permissions = Permission::where('group', $selected)
            ->select('id', 'name')
            ->get();

            $access = [];

            foreach($permissions as $permission) {

                $granted = 'false';
                
                if ($role_permissions) {
                    //IF HAS PERMISSIONS ON ROLE

                    foreach ($role_permissions->permissions as $role_permission) {
                        
                        if ($permission->id == $role_permission->id) {
                            //CHECK IF ROLE GRANTS THE PERMISSION

                            $granted = 'true';
                            break;
                        }
                    }
                }

                $access[] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'granted' => $granted
                ];

            }


            return DataTables::of($access)
            ->addColumn('grant', function($row){

                $checked = '';

                if($row['granted'] == 'true') $checked = 'checked';

                return '<td class="text-center"><input onclick="radioOptionClicked()" type="radio" name="permissions['.$row['name'].']" value="grant" '.$checked.'></td>';

            })
            ->addColumn('deny', function($row){

                $checked = '';

                if($row['granted'] == 'false') $checked = 'checked';

                return '<td class="text-center"><input onclick="radioOptionClicked()" type="radio" name="permissions['.$row['name'].']" value="deny" '.$checked.'></td>';

            })
            ->rawColumns(['grant', 'deny'])
            ->make(true);
            
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    public function updateRolePermission(Request $request, Role $role, $group)
    {
        try {
            $selected = "";

            if($group == 'roles') $selected = 'role';
            else if($group == 'users') $selected = 'user';
            else if($group == 'notes') $selected = 'note';
            
            $permissions = Permission::where('group', $selected)
            ->select('name')
            ->get();

            $keys = [];
            $values = [];

            foreach($permissions as $permission) {
                $keys[] = 'permissions.'.$permission->name.'';
                $values[] = "required";
            }

            $rules = array_combine($keys, $values);
            
            //VALIDATE
            $this->validate($request, $rules);

            $granted = [];
            $denied = [];

            foreach($request->permissions as $id => $permission){
                if($permission == 'grant') $granted[] = $id;
                else $denied[] = $id;
            }

            $role->revokePermissionTo($denied);
            $role->givePermissionTo($granted);

            return response()->json([
                'status'=>'SUCCESS'
            ], 200);

        } 
        catch (ValidationException $exception) {
            info($exception->errors());
            return response()->json([
                'status' => 'error',
                'msg'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json([
                'status'=>'ERROR',
                'message'=>$th->getMessage()
            ], 500);
        }
    }
}
