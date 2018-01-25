<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        //keep them in reverse order so they don't jump around
        return response($request->user()->notes()->orderBy('created_at', 'DESC')->get());
    }

    public function create(Request $request)
    {
        $validatedData = $this->validate($request, [
            'title' => 'max:255',
            'contents' => 'required',
        ]);

        $note = $request->user()->notes()->save(new Note($validatedData));

        return response($note);
    }

    public function update(Request $request, $noteId)
    {
        $validatedData = $this->validate($request, [
            'title' => 'max:255',
            'contents' => 'required',
        ]);

        $rowsUpdated = $request->user()->notes()->where('id', $noteId)->update($validatedData);

        return response(['success' => $rowsUpdated === 1]);
    }

    public function destroy(Request $request, $noteId)
    {
        $rowsUpdated = $request->user()->notes()->where('id', $noteId)->delete($noteId);

        return response(['success' => $rowsUpdated === 1]);
    }
}
