<?php

namespace App\Repositories\Task;

interface TaskRepositoryInterface{

    public function index();

    public function closeStatusindex();
    
    public function store($request);

    public function update($task , $request);

    public function closeStatus($task);

    public function delete($task);
}