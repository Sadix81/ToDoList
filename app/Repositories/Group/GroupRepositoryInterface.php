<?php

namespace App\Repositories\Group;

interface GroupRepositoryInterface
{
    public function index();

    public function store($request);

    public function update($group, $request);

    public function delete($group);
}
