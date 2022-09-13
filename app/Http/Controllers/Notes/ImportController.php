<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\ImportRequest;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Carbon;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    public function index() {
        return view('contents.notes.import');
    }

    public function import(ImportRequest $request) {
        
        try {

            $file = TemporaryFile::where('folder', $request->attachment)->first();

            //READING
            $notes = [];
            $rows = SimpleExcelReader::create(storage_path('app/temp/'.$file->folder.'/'.$file->filename))->getRows();
            foreach($rows as $row){
                $notes [] = [
                    'user_id' => Auth::user()->id,
                    'qr_code'=> $this->generateQR(),
                    'title' => $row['Title'],
                    'content' => $row['Content'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            //INSERTING
            foreach(array_chunk($notes, 10000) as $note) {
                Note::insert($note);
            }


            //CLEANING
            Storage::deleteDirectory('temp/'. $file->folder);
            $file->delete();

            return response()->json([
                'title'=> 'Import success',
                'message' => 'Data has been imported successfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'title'=> 'Import failed',
                'message' => $th->getMessage()
            ], 500);
        }

    }
}
