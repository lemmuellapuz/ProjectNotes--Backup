<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Note;

class NoteFile extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'folder',
        'filename'
    ];

    public function note() {
        return $this->belongsTo(Note::class);
    }
}
