<?php

namespace App\Repositories\Note;

interface NoteRepositoryInterface
{
    public function index($task);

    public function store($task, $request);

    public function update($task, $note, $request);

    public function delete($task, $note);
}
