<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function index()
    {
        $categories = Category::query()->latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create([...$request->validated(), 'is_active' => $request->boolean('is_active')]);
        $this->audit->log($request, 'create', 'category', $category->id, 'Created category');

        return back()->with('success', __('messages.saved'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([...$request->validated(), 'is_active' => $request->boolean('is_active')]);
        $this->audit->log($request, 'update', 'category', $category->id, 'Updated category');

        return back()->with('success', __('messages.saved'));
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();
        $this->audit->log($request, 'delete', 'category', $category->id, 'Deleted category');

        return response()->json(['ok' => true]);
    }
}
