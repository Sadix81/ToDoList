<?php

namespace App\Repositories\Note;

use App\Models\Note\Note;
use App\Models\Task\Task;
use Illuminate\Support\Facades\Auth;

class NoteRepository implements NoteRepositoryInterface
{
    public function index($task)
    {
        $task = Task::findOrFail($task);
        try {
            $note = Note::where('task_id', $task->id)->get();

            return $note;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store($task, $request)
    {

        $user_id = Auth::id();

        try {
            Note::create([
                'description' => $request->description,
                'user_id' => $request->user_id ?: $user_id,
                'task_id' => $task->id,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($task, $note, $request)
    {

        $user_id = Auth::id();

        $note = Note::where('task_id', $task->id)
            ->where('id', $note->id)->firstOrFail();

        try {
            $note->update([
                'description' => $request->description ? $request->description : $note->description,
                'user_id' => $request->user_id ?: $user_id,
                'task_id' => $task->id,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($task, $note)
    {
        try {
            $note = Note::where('task_id', $task->id)
                ->where('id', $note->id)->first();

            $note->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
