<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Note_share;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index(Request $request)
    {

        $notes = $request->user()
                         ->notes()
                         ->with('shares')
                         ->orderBy('created_at', 'DESC')
                         ->get()
                         ->map(function ($note) {
                             $note['is_shared'] = false;

                             return $note;
                         });
        $sharedNotes = $request
            ->user()
            ->sharedNotes()
            ->with('user', 'lastModifiedByUser')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function ($note) {
                $note['is_shared'] = true;

                return $note;
            });

        return response($notes->concat($sharedNotes));
    }

    public function create(Request $request)
    {
        $validatedData = $this->validate($request, [
            'title' => 'string|max:255|nullable',
            'contents' => 'string|nullable',
            'shares' => 'array',
        ]);

        $note = $request->user()->notes()->save(new Note($validatedData));
        $this->updateShares($note, $validatedData['shares']);

        return response($note);
    }

    public function update(Request $request, $noteId)
    {
        $validatedData = $this->validate($request, [
            'title' => 'string|max:255|nullable',
            'contents' => 'string|nullable',
            'shares' => 'array',
        ]);

        $note = $request->user()->notes()->where('id', $noteId)->first();

        // if the note doesn't exist, it might be shared
        if (!$note) {
            $note = $request->user()->sharedNotes()->where('notes.id', $noteId)->firstOrFail();
        }

        $note->update(array_merge($validatedData, ['last_modified_by_user_id' => $request->user()->id]));

        if (isset($validatedData['shares'])){
            $this->updateShares($note, $validatedData['shares']);
        }

        return response(['success' => true]);
    }

    public function destroy(Request $request, $noteId)
    {
        $rowsUpdated = $request->user()->notes()->where('id', $noteId)->delete($noteId);

        return response(['success' => $rowsUpdated === 1]);
    }

    private function updateShares(Note $note, $shares)
    {
        $note->shares()->delete();
        foreach ($shares as $share) {
            //is the email valid?
            if (filter_var($share['email'], FILTER_VALIDATE_EMAIL)) {
                Note_share::create(['note_id' => $note->id, 'email' => $share['email'], 'is_read_only' => 0]);
            }
        }
    }
}
