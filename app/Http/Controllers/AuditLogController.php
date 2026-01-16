<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(): View
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(50);

        return view('admin.audit-logs.index', compact('logs'));
    }
}
