<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddUserRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

use App\Models\User;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }
    
    public function index()
    {
        return view('contents.user.dashboard');
    }

    public function table()
    {
        $users = User::where('id','!=',Auth::user()->id);

        return DataTables::of($users)
        ->addColumn('actions', function($row){

            $btn = '';

            if(Auth::user()->can('view-user'))
                $btn = $btn . '<a class="btn btn-secondary mx-1" href="' . route('user.show', ['user'=> $row]) . '">View</a>';
            
            if(Auth::user()->can('update-user'))
                $btn = $btn . '<a class="btn btn-primary mx-1" href="'.route('user.edit', ['user' => $row]).'">Edit</a>';

            if(Auth::user()->can('delete-user'))
                $btn = $btn . 
                '<form class="mx-1" action="'. route('user.destroy', ['user' => $row]) .'" method="POST">
                    '. method_field('DELETE') .'
                    '.csrf_field().'
                    <input type="submit" value="Delete" class="btn btn-danger">
                </form>';

            return '<div class="d-flex">' . $btn . '</div>';
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
    
    public function create()
    {
        return view('contents.user.create');
    }
    
    public function store(AddUserRequest $request)
    {
        try {
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $this->storeAttachment($user, $request);

            $user->assignRole('user');
    
            return redirect()->route('user.index')->with('success', 'User created');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    public function show(User $user)
    {
        return view('contents.user.view')->with('user', $user);
    }
    
    public function edit(User $user)
    {
        return view('contents.user.edit')->with('user', $user);
    }
    
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $this->storeAttachment($user, $request);
    
            return redirect()->route('user.index')->with('success', 'User updated');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function updatePassword(UpdatePasswordRequest $request, User $user) {
        try {
            
            if( Hash::check($request->old_password, $user->password) ) return redirect()->back()->with('error', 'New password cannot be the same as previous password.');

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->back()->with('success', 'Password updated.');
            
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    public function destroy(User $user)
    {
        try {
           $user->delete();

            return redirect()->back()->with('success', 'User deleted');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    private function storeAttachment($user, $request) {
        //MOVING FILES FROM TEMP TO FINAL DIRECTORY
        if(isset($request->profile)) {
            $file_data = TemporaryFile::where('folder', $request->profile)->first();

            if($file_data) {

                if($user->profile) {
                    //IF USER HAS EXISTING PROFILE IMAGE
                    Storage::deleteDirectory('user/'.$user->path);
                }
                
                
                $folder = $file_data->folder;
                $old_filename = $file_data->filename;
                $new_filename = Uuid::uuid4() . '-' . $old_filename;
                $new_folder = Uuid::uuid4() . '-' . $folder;

                Storage::move('temp/'.$folder.'/'.$old_filename, 'user/'.$new_folder.'/'.$new_filename);
                Storage::deleteDirectory('temp/'.$file_data->folder);

                $file_data->delete();

                $user->update([
                    'profile' => $new_filename,
                    'path' => $new_folder
                ]);

                
            }

        }
    }

}
