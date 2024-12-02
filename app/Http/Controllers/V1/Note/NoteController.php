<?php

namespace App\Http\Controllers\V1\Note;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\CreateNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\Note\IndexNoteResource;
use App\Http\Resources\Note\ShowNoteResource;
use App\Models\Note\Note;
use App\Models\Task\Task;
use App\Repositories\Note\NoteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    private $noteRepo;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepo = $noteRepository;
    }

    public function index($task)
    {
        return IndexNoteResource::collection($this->noteRepo->index($task));
    }

    public function store(Task $task , CreateNoteRequest $request)
    {
        $auth = Auth::id();

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if(! $task){
            return response()->json(['message' => 'تسکی یافت نشد']);
        }
        
        $error = $this->noteRepo->store($task , $request);
        if($error === null){
            return response()->json(['message' => __('messages.note.store.seccuess' , ['description' => $request->description])] , 201);
        }
        return response()->json(['message' => __('messages.note.store.failed')] , 500);
    }

    public function show(Task $task , Note $note){

        $auth = Auth::id();

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if(! $note){
            return response()->json(['message' => 'موردی یافت نشد']);
        }

        if($task->id && ($note->task_id !== $task->id)){
            return response()->json(['message' => 'عدم دسترسی'] , 404);
        }

        return new ShowNoteResource($note);
    }

    public function update(Task $task , Note $note , UpdateNoteRequest $request)
    {
        $auth = Auth::id();

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if(! $note){
            return response()->json(['message' => 'موردی یافت نشد']);
        }

        if($task->id && ($note->task_id !== $task->id)){
            return response()->json(['message' => 'عدم دسترسی'] , 404);
        }
        
        $error = $this->noteRepo->update($task , $note , $request);
        if($error === null){
            return response()->json(['message' => __('messages.note.update.seccuess' , ['description' => $request->description])] , 200);
        }
        return response()->json(['message' => __('messages.note.update.failed')] , 500);
    }

    public function destroy(Task $task , Note $note)
    {
        $auth = Auth::id();

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }
        
        if(! $task){
            return response()->json(['message' => 'تسکی یافت نشد']) ;
        }

        if(! $note){
            return response()->json(['message' => 'موردی یافت نشد']);
        }
        
        if($task->id && ($note->task_id !== $task->id)){
            return response()->json(['message' => 'تسک مورد نظر یافت نشد']) ;
        }
    
        $error = $this->noteRepo->delete($task , $note);
        if($error === null){
            return response()->json(['message' => __('messages.note.delete.seccuess' , ['description' => $note->description])] , 200);
        }
        return response()->json(['message' => __('messages.note.delete.failed')] , 500);
    }
}
