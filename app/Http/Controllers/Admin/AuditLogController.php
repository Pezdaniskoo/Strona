<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function __invoke()
    {
        $logs = AuditLog::query()->with('user')->latest('created_at')->paginate(30);

        return view('admin.audit-logs.index', compact('logs'));
    }
}
