<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\TemporaryFile;

use Illuminate\Support\Facades\Storage;

class UserFileController extends Controller
{
    public function get(User $user) {
        return response()->file(storage_path('app/user/'.$user->path.'/'.$user->profile));
    }

    public function download(User $user) {

        try {
            
            $ext = pathinfo('app/user/'.$user->path.'/'.$user->profile, PATHINFO_EXTENSION);
            return Storage::download('user/'.$user->path.'/'.$user->profile, uniqid().'.'.$ext);

        } catch (\Throwable $th) {
            abort(404);
        }

    }

    public function store(Request $request) {
        
        if($request->hasFile('profile')) {
        
            $file = $request->file('profile');
            $folder = uniqid() . '-' . now()->timestamp;
            $filename = $folder . '.' . $file->getClientOriginalExtension();

            $file->storeAs( 'temp/'.$folder, $filename );

            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename
            ]);

            return $folder;
        }

        return '';
        
    }

    public function revert() {

        $folder = request()->getContent();

        TemporaryFile::where('folder', $folder)->delete();
        Storage::deleteDirectory('temp/' . $folder);
        
        return '';

    }
}
