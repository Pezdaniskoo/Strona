<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard.index', [
            'stats' => [
                'users' => User::count(),
                'tasks' => Task::count(),
                'done_tasks' => Task::where('status', TaskStatus::DONE->value)->count(),
                'active_tasks' => Task::whereIn('status', [TaskStatus::NEW->value, TaskStatus::IN_PROGRESS->value])->count(),
                'categories' => Category::count(),
            ],
        ]);
    }
}
