<?php

namespace App\Repositories\Note;

use App\Models\Note\Note;
use Illuminate\Support\Facades\Auth;

class NoteRepository implements NoteRepositoryInterface
{
    public function store($request){

        $user_id = Auth::id();
        
        try {
            Note::create([
                'description' => $request->description,
                'user_id' => $request->user_id ?: $user_id,
                'task_id' => $request->task_id,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($note, $request){

        $user_id = Auth::id();

        try {
            $note->update([
                'description' => $request->description ? $request->description : $note->description,
                'user_id' => $request->user_id ?: $user_id,
                'task_id' => $request->task_id ? $request->task_id : $note->task_id,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($note){
        try {
            $note->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}