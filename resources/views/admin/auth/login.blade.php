<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-5"><div class="card"><div class="card-body">
<h4>Admin login</h4>
<form method="post" action="{{ route('admin.login.submit') }}">@csrf
<input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
<input class="form-control mb-2" type="password" name="password" placeholder="Password" required>
<button class="btn btn-primary w-100">Login</button>
</form></div></div></div></div></div></body></html>
