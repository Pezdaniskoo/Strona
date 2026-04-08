@extends('layouts.admin')
@section('content')
<h4>Audit logs</h4>
<table class="table table-sm"><thead><tr><th>Date</th><th>Admin</th><th>Action</th><th>Entity</th><th>Description</th></tr></thead><tbody>
@foreach($logs as $log)
<tr><td>{{ $log->created_at }}</td><td>{{ $log->user?->email }}</td><td>{{ $log->action }}</td><td>{{ $log->entity_type }}#{{ $log->entity_id }}</td><td>{{ $log->description }}</td></tr>
@endforeach
</tbody></table>{{ $logs->links() }}
@endsection
