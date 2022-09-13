<?php

namespace App\Http\Controllers;

use App\Models\NoteFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteFileController extends Controller
{
    public function get(NoteFile $notefile) {
        return response()->file(storage_path('app/uploads/'.$notefile->filename));
    }

    public function download(NoteFile $notefile) {

        try {
            
            $ext = pathinfo('app/uploads/'.$notefile->filename, PATHINFO_EXTENSION);
            return Storage::download('uploads/'.$notefile->filename, uniqid().'.'.$ext);

        } catch (\Throwable $th) {
            abort(404);
        }

    }

    public function destroy(NoteFile $notefile) {
        try {
            
            Storage::delete('uploads/'.$notefile->filename);

            $notefile->delete();

            return response()->json([
                'message'=> 'Attachment deleted',
                'status'=> 'Success'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Error'
            ], 500);
        }
    }
}
