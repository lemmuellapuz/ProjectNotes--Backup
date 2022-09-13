<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class TemporaryFileController extends Controller
{
    public function store(Request $request) {
        
        if($request->hasFile('attachment')) {
        
            $file = $request->file('attachment');
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
