@extends('layouts.admin')
@section('content')
<h4>Tasks</h4>
<form class="row g-2 mb-3" method="get">
<div class="col"><select name="status" class="form-select"><option value="">All statuses</option>@foreach(['new','in_progress','done','archived'] as $status)<option @selected(request('status')===$status) value="{{ $status }}">{{ $status }}</option>@endforeach</select></div>
<div class="col"><select name="category_id" class="form-select"><option value="">All categories</option>@foreach($categories as $category)<option @selected((string)request('category_id')===(string)$category->id) value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
<div class="col"><button class="btn btn-primary">Filter</button></div>
</form>
<table class="table table-striped"><thead><tr><th>Task</th><th>User</th><th>Status</th><th>Due</th></tr></thead><tbody>
@foreach($tasks as $task)
<tr>
<td>{{ $task->title }}</td><td>{{ $task->user->email }}</td>
<td><select class="form-select form-select-sm" onchange="quickStatus({{ $task->id }}, this.value)">@foreach(['new','in_progress','done','archived'] as $s)<option @selected($task->status->value===$s) value="{{ $s }}">{{ $s }}</option>@endforeach</select></td>
<td>{{ optional($task->due_date)->toDateString() }}</td>
</tr>
@endforeach
</tbody></table>{{ $tasks->links() }}
<script>
function quickStatus(id,status){patch(`/admin/tasks/${id}/quick-status`,{status}).then(()=>{})}
</script>
@endsection
