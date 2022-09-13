<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;

use App\Models\Note;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function generateQR() {
        $lastId = Note::orderBy('created_at', 'DESC')->limit(1)->first();

        if($lastId) $lastId = $lastId->id;
        else $lastId = 1;
        
        $qr = base64_encode(uniqid($lastId, true));
        return $qr;
    }
}
