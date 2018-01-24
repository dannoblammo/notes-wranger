<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NotesController extends Controller
{

    public function create(Request $request)
    {
        $validatedData = $this->validate($request, [
            'title' => 'required|max:255',
            'contents' => 'required',
        ]);

        $note = $request->user()->notes()->save(new Note($validatedData));

        return response($note);
    }
}
