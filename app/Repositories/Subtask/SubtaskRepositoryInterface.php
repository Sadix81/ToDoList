<?php

namespace App\Repositories\Subtask;

interface SubtaskRepositoryInterface
{
    // public function index();

    public function store($request);

    public function update($subtask, $request);

    public function delete($subtask);
}
