<?php

namespace App\Repositories\Profile;

interface ProfileRepositoryInterface
{
    public function update($user, $request);

    public function changePassword($request);
}
