<?php

namespace App\Repositories\Task;

use App\Models\Task\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
            'finished_at' => request()->has('finished_at') ? request('finished_at') : null,
            'priority' => request()->has('priority') ? request('priority') : null,
            'status' => request()->has('status') ? 1 : null,
            // 'category' => request()->has('category') ? request('category') : null,
        ];
        try {
            $task = Task::whereHas('user', function ($query) use ($req) {
                $query->where('user_id', Auth::id());
                if ($req['search']) {
                    $query->where('title', 'Like', '%'.$req['search'].'%');
                }
                if ($req['finished_at']) {
                    $query->where('finished_at', 'Like', '%'.$req['finished_at'].'%');
                }
                if ($req['priority']) {
                    $query->where('priority', 'Like', '%'.$req['priority'].'%');
                }
                if ($req['status']) {
                    $query->where('status', 1);
                }
            })
                ->orderBy($req['sort'], $req['order'])
                ->paginate($req['limit']);

            return $task;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function closeStatusindex()
    {
        try {
            $tasks = Task::where('status', 1)->latest()->get();

            return $tasks;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store($request)
    {
        $user = Auth::id();

        DB::beginTransaction();

        if (request()->hasFile('image')) {
            $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $image_name);
        }

        try {
            $task = Task::create([
                'user_id' => $request->user_id ?: $user,
                'title' => $request->title,
                'description' => $request->description,
                'started_at' => $request->started_at ? $request->started_at : Carbon::now(),
                'finished_at' => $request->finished_at,
                'priority' => $request->priority,
                'status' => $request->status ?: 0,
                'image' => $request->image ? $image_name : null,
            ]);
            if ($request->has('category_id')) {
                $task->categories()->attach($request->category_id);
            }
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($task, $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $image_name = $task->image;

            // Check if an image has been uploaded
            if ($request->hasFile('image')) {
                $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
                $request->image->move(public_path('images'), $image_name);
            } elseif ($request->image === null) {
                $image_name = null;
                if ($task->image && file_exists(public_path('images/'.$task->image))) {
                    unlink(public_path('images/'.$task->image));
                }
            }

            $task->update([
                'user_id' => $request->user_id ?: $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'started_at' => $request->started_at ? $request->started_at : $task->started_at,
                'finished_at' => $request->finished_at ? $request->finished_at : $task->finished_at,
                'priority' => $request->priority ? $request->priority : $task->priority,
                'status' => $request->status !== null ?  $request->status : $task->status,
                'image' => $image_name,
            ]);
            if ($request->has('category_id')) {
                $task->categories()->sync($request->category_id);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function closeStatus($task)
    {
        try {
            // XOR
            $task->status = $task->status ^ 1;
            $task->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($task)
    {
        try {
            $task->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
