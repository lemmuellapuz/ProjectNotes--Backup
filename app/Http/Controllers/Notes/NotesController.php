<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Notes\CreateNoteRequest;
use App\Http\Requests\Notes\QrSearchRequest;
use App\Http\Requests\Notes\UpdateNoteRequest;
use App\Models\Note;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use DB;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class NotesController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Note::class, 'note');
    }
   
    public function index()
    {
        // return QrCode::generate('Make me into a QrCode!');
        // return $this->genereateQr(3);
        return view('contents.notes.dashboard');
    }

    public function table()
    {
        $notes = "";
        
        Auth::user()->roles->pluck('name')->first() == 'admin' ? 
        $notes = Note::whereNot('created_at', '') : $notes = Note::where('user_id', Auth::user()->id);
        

        return DataTables::of($notes)
        ->addColumn('actions', function($row){

            $btn = '';

            if(Auth::user()->can('view-note'))
                $btn = $btn . '<a class="btn btn-primary mx-1" onclick="openQrModal(\''.$row->qr_code.'\')">Download QR</a>';
            
            if(Auth::user()->can('update-note'))
                $btn = $btn . '<a class="btn btn-secondary mx-1" href="'.route('notes.edit', ['note' => $row]).'">Edit</a>';

            if(Auth::user()->can('delete-note'))
                $btn = $btn . '
                <form action="'. route('notes.destroy', ['note' => $row]) .'" method="POST">
                    '. method_field('DELETE') .'
                    '.csrf_field().'
                    <input type="submit" value="Delete" class="btn btn-danger mx-1">
                </form>
            ';

            return '<div class="d-flex">' . $btn . '</div>';
        })
        ->rawColumns(['actions'])
        ->make(true);
    }

   
    public function create()
    {
        return view('contents.notes.create');
    }


    public function store(CreateNoteRequest $request)
    {
        try {
            
            

            $note = Auth::user()->notes()->create([
                'qr_code' => $this->generateQR(),
                'title' => $request->title,
                'content' => $request->content
            ]);

            $this->storeAttachment($note, $request);
    
            return redirect()->route('notes.index')->with('success', 'Notes added');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(Note $note)
    {
        return view('contents.notes.view')->with('note', $note);
    }

    public function showViaQr(QrSearchRequest $request) {
        try {
            
            $note = Note::where('qr_code', $request->qr)->first();

            if($note) return response()->json([
                'data' => $note->id,
                'status' => 'Success'
            ], 200);
            
            return response()->json([
                'message' => 'Data not found.',
                'status' => 'Error'
            ], 500);

        } catch (\Throwable $th) {
            
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Error'
            ], 500);

        }
    }

    public function edit(Note $note)
    {
        $attachment = $note->files()->first();

        return view('contents.notes.edit', compact('note', 'attachment'));
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        try {
            
            $note->update([
                'title' => $request->title,
                'content' => $request->content
            ]);

            $this->storeAttachment($note, $request);
    
            return redirect()->route('notes.index')->with('success', 'Note updated');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Note $note)
    {
        try {
            Storage::delete('uploads/'.$note->files()->first()->filename);

            $note->delete();

            return redirect()->back()->with('success', 'Note deleted');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    private function storeAttachment($note, $request) {
        //MOVING FILES FROM TEMP TO FINAL DIRECTORY
        if($request->attachment) {
            $file_data = TemporaryFile::where('folder', $request->attachment)->first();

            $folder = $file_data->folder;
            $old_filename = $file_data->filename;
            $new_filename = Uuid::uuid4() . '-' . $old_filename;

            Storage::move('temp/'.$folder.'/'.$old_filename, 'uploads/'.$new_filename);
            Storage::deleteDirectory('temp/'.$file_data->folder);

            $file_data->delete();

            $note->files()->create([
                'filename' => $new_filename
            ]);

        }
    }
}
