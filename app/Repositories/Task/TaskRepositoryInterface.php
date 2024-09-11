<?php

namespace App\Repositories\Task;

interface TaskRepositoryInterface{

    public function index();
    
    public function store($request);

    public function update($task , $request);

    public function delete($task);
}