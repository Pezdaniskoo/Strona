@extends('layouts.admin')
@section('content')
<h4>Edit user</h4>
<form method="post" action="{{ route('admin.users.update',$user) }}">@csrf @method('put')
<input class="form-control mb-2" name="first_name" value="{{ $user->first_name }}" required>
<input class="form-control mb-2" name="last_name" value="{{ $user->last_name }}" required>
<input class="form-control mb-2" name="email" type="email" value="{{ $user->email }}" required>
<select class="form-select mb-2" name="locale"><option value="pl" @selected($user->locale==='pl')>pl</option><option value="en" @selected($user->locale==='en')>en</option></select>
<button class="btn btn-primary">Save</button></form>
@endsection
