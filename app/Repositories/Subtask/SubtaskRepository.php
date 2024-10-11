<?php

namespace App\Repositories\Subtask;

use App\Models\Subtask\Subtask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubtaskRepository implements SubtaskRepositoryInterface
{
    public function store($request)
    {
        $user = Auth::id();

        DB::beginTransaction();

        if (request()->hasFile('image')) {
            $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $image_name);
        }

        try {
            $subtask = Subtask::create([
                'user_id' => $request->user_id ?: $user,
                'task_id' => $request->task_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status ?: 0,
                'image' => $request->image ? $image_name : null,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($subtask, $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $image_name = $subtask->image;

            // Check if an image has been uploaded
            if ($request->hasFile('image')) {
                $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
                $request->image->move(public_path('images'), $image_name);
            } elseif ($request->image === null) {
                $image_name = null;
                if ($subtask->image && file_exists(public_path('images/'.$subtask->image))) {
                    unlink(public_path('images/'.$subtask->image));
                }
            }

            $subtask->update([
                'user_id' => $request->user_id ?: $user->id,
                'task_id' => $request->task_id ? $request->task_id : $subtask->task_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status !== null ?  $request->status : $subtask->status,
                'image' => $image_name,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete($subtask)
    {
        try {
            $subtask->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
