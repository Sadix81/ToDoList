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
        $user = Auth::id();
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'search' => request()->has('search') ? request('search') : null,
            'started_at' => request()->has('started_at') ? request('started_at') : null,
            'finished_at' => request()->has('finished_at') ? request('finished_at') : null,
            'priority' => request()->has('priority') ? request('priority') : null,
        ];
        try {
            $task = Task::whereHas('tasks', function ($query) use ($req) {
                $query->where('user_id', $user);
                if ($req['search']) {
                    $query->where('title', 'Like', '%' . $req['search'] . '%');
                }
                if ($req['started_at']) {
                    $query->where('started_at', 'Like', '%' . $req['started_at'] . '%');
                }
                if ($req['finished_at']) {
                    $query->where('finished_at', 'Like', '%' . $req['finished_at'] . '%');
                }
                if ($req['priority']) {
                    $query->where('priority', 'Like', '%' . $req['priority'] . '%');
                }
            })
                ->orderBy($req['sort'], $req['order'])
                ->paginate($req['limit']);

                return $task;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store($request)
    {
        $user = Auth::id();
        try {
            Task::create([
                'user_id' => $request->user_id ?: $user,
                'title' => $request->title,
                'description' => $request->description,
                'started_at' => $request->started_at ? $request->started_at : Carbon::now(),
                'finished_at' => $request->finished_at,
                'priority' => $request->priority,
                'reminder' => $request->reminder,
                'label' => $request->label,
                'status' => $request->status,
                // 'storage_id',
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($task, $request)
    {
        $user = Auth::id();
        try {
            $task->update([
                'user_id' => $request->user_id ? $user : $user,
                'title' => $request->title,
                'description' => $request->description,
                'started_at' => $request->started_at ? $request->started_at : $task->started_at,
                'finished_at' => $request->finished_at ? $request->finished_at : $task->finished_at,
                'priority' => $request->priority,
                'reminder' => $request->reminder,
                'label' => $request->label,
                'status' => $request->status,
            ]);
            DB::commit();
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
