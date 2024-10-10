<?php

namespace App\Repositories\Note;

interface NoteRepositoryInterface
{
    public function index();

    public function store($request);

    public function update($note, $request);

    public function delete($note);
}