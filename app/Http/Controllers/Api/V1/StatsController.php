<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = request()->user()->tasks();

        return response()->json([
            'data' => [
                'all_tasks' => $tasks->count(),
                'done_tasks' => (clone $tasks)->where('status', TaskStatus::DONE->value)->count(),
                'active_tasks' => (clone $tasks)->whereIn('status', [TaskStatus::NEW->value, TaskStatus::IN_PROGRESS->value])->count(),
            ],
        ]);
    }
}
