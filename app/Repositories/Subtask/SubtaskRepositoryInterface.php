<?php

namespace App\Repositories\Subtask;

interface SubtaskRepositoryInterface
{
    public function index($task);

    public function store($task , $request);

    public function update($task , $subtask, $request);

    public function delete($task , $subtask);
}
