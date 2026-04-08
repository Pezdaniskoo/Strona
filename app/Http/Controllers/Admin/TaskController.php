<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Task;
use App\Services\AuditLogService;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private readonly AuditLogService $audit, private readonly HolidayService $holidayService)
    {
    }

    public function index()
    {
        $query = Task::query()->with(['user', 'category'])->latest();

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($categoryId = request('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $tasks = $query->paginate(15);
        $categories = Category::orderBy('name')->get();

        return view('admin.tasks.index', compact('tasks', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:new,in_progress,done,archived'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $data['completed_at'] = $data['status'] === TaskStatus::DONE->value ? now() : null;
        $task->update($data);
        $this->audit->log($request, 'update', 'task', $task->id, 'Updated task');

        return back()->with('success', __('messages.saved'));
    }

    public function quickStatus(Request $request, Task $task)
    {
        $status = $request->validate(['status' => ['required', 'in:new,in_progress,done,archived']])['status'];

        $task->update([
            'status' => $status,
            'completed_at' => $status === TaskStatus::DONE->value ? now() : null,
        ]);

        $this->audit->log($request, 'status', 'task', $task->id, 'Changed task status quickly');

        return response()->json([
            'status' => $task->status->value,
            'is_due_date_holiday' => $this->holidayService->isHoliday(optional($task->due_date)?->toDateString()),
        ]);
    }
}
