<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaskStatusRequest;
use App\Http\Requests\Api\TaskStoreRequest;
use App\Http\Requests\Api\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\HolidayService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(private readonly HolidayService $holidayService)
    {
    }

    public function index(): JsonResponse
    {
        $query = request()->user()->tasks()->with('category');

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($priority = request('priority')) {
            $query->where('priority', $priority);
        }

        if ($categoryId = request('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($search = request('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = request('sort', 'created_at');
        $direction = request('direction', 'desc');

        $tasks = $query->orderBy(in_array($sort, ['created_at', 'due_date'], true) ? $sort : 'created_at', $direction)
            ->paginate(20);

        $tasks->getCollection()->transform(function ($task) {
            $task->is_due_date_holiday = $this->holidayService->isHoliday(optional($task->due_date)?->toDateString());

            return $task;
        });

        return response()->json($tasks);
    }

    public function store(TaskStoreRequest $request): JsonResponse
    {
        $task = $request->user()->tasks()->create([
            ...$request->validated(),
            'status' => $request->input('status', TaskStatus::NEW->value),
        ]);

        $task->load('category');
        $task->is_due_date_holiday = $this->holidayService->isHoliday(optional($task->due_date)?->toDateString());

        return response()->json([
            'message' => __('messages.task_created'),
            'data' => new TaskResource($task),
        ], 201);
    }

    public function show(Task $task): JsonResponse
    {
        abort_if($task->user_id !== request()->user()->id, 403);

        $task->load('category');
        $task->is_due_date_holiday = $this->holidayService->isHoliday(optional($task->due_date)?->toDateString());

        return response()->json(['data' => new TaskResource($task)]);
    }

    public function update(TaskUpdateRequest $request, Task $task): JsonResponse
    {
        abort_if($task->user_id !== request()->user()->id, 403);

        $task->update($request->validated());

        return response()->json([
            'message' => __('messages.task_updated'),
            'data' => new TaskResource($task->fresh(['category'])),
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        abort_if($task->user_id !== request()->user()->id, 403);
        $task->delete();

        return response()->json(['message' => __('messages.task_deleted')]);
    }

    public function status(TaskStatusRequest $request, Task $task): JsonResponse
    {
        abort_if($task->user_id !== request()->user()->id, 403);

        $status = $request->string('status')->toString();

        $task->update([
            'status' => $status,
            'completed_at' => $status === TaskStatus::DONE->value ? now() : null,
        ]);

        return response()->json(['message' => __('messages.task_status_updated')]);
    }

    public function complete(Task $task): JsonResponse
    {
        abort_if($task->user_id !== request()->user()->id, 403);

        $task->update([
            'status' => TaskStatus::DONE->value,
            'completed_at' => now(),
        ]);

        return response()->json(['message' => __('messages.task_completed')]);
    }

    public function archive(Task $task): JsonResponse
    {
        abort_if($task->user_id !== request()->user()->id, 403);

        $task->update(['status' => TaskStatus::ARCHIVED->value]);

        return response()->json(['message' => __('messages.task_archived')]);
    }
}
