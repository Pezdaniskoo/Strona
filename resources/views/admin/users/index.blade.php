@extends('layouts.admin')
@section('content')
<h4>Users</h4>
<table class="table table-striped"><thead><tr><th>ID</th><th>Email</th><th>Name</th><th>Active</th><th></th></tr></thead><tbody>
@foreach($users as $user)
<tr>
<td>{{ $user->id }}</td><td>{{ $user->email }}</td><td>{{ $user->first_name }} {{ $user->last_name }}</td>
<td><button class="btn btn-sm {{ $user->is_active ? 'btn-success':'btn-secondary' }}" onclick="toggleUser({{ $user->id }})">{{ $user->is_active ? 'Yes':'No' }}</button></td>
<td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit',$user) }}">Edit</a></td>
</tr>
@endforeach
</tbody></table>
{{ $users->links() }}
<script>
function toggleUser(id){patch(`/admin/users/${id}/toggle-active`).then(()=>location.reload())}
</script>
@endsection
