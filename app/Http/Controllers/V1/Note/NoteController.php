<?php

namespace App\Http\Controllers\V1\Note;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\CreateNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\Note\ShowNoteResource;
use App\Models\Note\Note;
use App\Repositories\Note\NoteRepository;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    private $noteRepo;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepo = $noteRepository;
    }

    public function store(CreateNoteRequest $request){
        
        $error = $this->noteRepo->store($request);
        if($error === null){
            return response()->json(['message' => __('messages.note.store.seccuess' , ['description' => $request->description])] , 201);
        }
        return response()->json(['message' => __('messages.note.store.failed')] , 500);
    }

    public function show(Note $note){
        return new ShowNoteResource($note);
    }

    public function update(Note $note , UpdateNoteRequest $request){

        if((int)$request->task_id !== $note->task_id){
            return response()->json(['message' => 'عدم دسترسی'] , 404);
        }
        
        $error = $this->noteRepo->update($note , $request);
        if($error === null){
            return response()->json(['message' => __('messages.note.update.seccuess' , ['description' => $request->description])] , 200);
        }
        return response()->json(['message' => __('messages.note.update.failed')] , 500);
    }

    public function destroy(Note $note){
        
        $error = $this->noteRepo->delete($note);
        if($error === null){
            return response()->json(['message' => __('messages.note.delete.seccuess' , ['description' => $note->description])] , 200);
        }
        return response()->json(['message' => __('messages.note.delete.failed')] , 500);
    }
}
