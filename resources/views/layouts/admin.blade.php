<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ToDo Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row min-vh-100">
        <aside class="col-12 col-md-3 col-lg-2 bg-dark text-white p-3">
            <h5>ToDo Admin</h5>
            <nav class="nav flex-column">
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link text-white" href="{{ route('admin.users.index') }}">Users</a>
                <a class="nav-link text-white" href="{{ route('admin.categories.index') }}">Categories</a>
                <a class="nav-link text-white" href="{{ route('admin.tasks.index') }}">Tasks</a>
                <a class="nav-link text-white" href="{{ route('admin.audit-logs.index') }}">Audit logs</a>
                <a class="nav-link text-white" href="{{ route('admin.mail.index') }}">Mail</a>
            </nav>
        </aside>
        <main class="col-12 col-md-9 col-lg-10 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form method="post" action="{{ route('admin.locale') }}" class="d-flex gap-2">
                    @csrf
                    <select name="locale" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="pl" @selected(app()->getLocale()==='pl')>PL</option>
                        <option value="en" @selected(app()->getLocale()==='en')>EN</option>
                    </select>
                </form>
                <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-outline-danger btn-sm">Logout</button></form>
            </div>
            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
            @yield('content')
        </main>
    </div>
</div>
<script>
async function patch(url, body = {}) {
  return fetch(url, {method:'PATCH',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Content-Type':'application/json'},body:JSON.stringify(body)});
}
</script>
</body>
</html>
