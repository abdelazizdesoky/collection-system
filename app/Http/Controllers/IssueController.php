<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    public function index(Request $request): View
    {
        $showTrashed = $request->input('trashed') === '1';
        $query = Issue::with(['customer', 'collector', 'visit']);

        if ($showTrashed) {
            $query->onlyTrashed();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $issues = $query->latest()->paginate(15);

        $stats = [
            'pending' => Issue::where('status', 'pending')->count(),
            'processing' => Issue::where('status', 'processing')->count(),
            'resolved' => Issue::where('status', 'resolved')->count(),
            'escalated' => Issue::where('status', 'escalated')->count(),
            'closed' => Issue::where('status', 'closed')->count(),
            'total' => Issue::count(),
        ];

        $trashedCount = Issue::onlyTrashed()->count();
        $activeCount = Issue::count();

        return view('issues.index', compact('issues', 'stats', 'showTrashed', 'trashedCount', 'activeCount'));
    }

    public function show(Issue $issue): View
    {
        $issue->load(['customer', 'collector', 'visit.visitPlanItem.visitPlan']);
        return view('issues.show', compact('issue'));
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,resolved,escalated,closed',
            'priority' => 'required|string|in:low,normal,high,urgent',
            'escalation_reason' => 'required_if:status,escalated|nullable|string|max:1000',
            'resolution_notes' => 'required_if:status,resolved|nullable|string|max:1000',
            'closure_reason' => 'required_if:status,closed|nullable|string|max:1000',
        ]);

        $issue->update($validated);

        return redirect()->route('issues.show', $issue)
            ->with('success', 'تم تحديث حالة المشكلة بنجاح.');
    }

    /**
     * Remove the specified issue from storage (Soft Delete).
     */
    public function destroy(Issue $issue)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'plan_supervisor'])) {
            abort(403);
        }

        $issue->delete();

        return redirect()->route('issues.index')
            ->with('success', 'تم نقل المشكلة إلى المحذوفات مؤقتاً.');
    }

    /**
     * Restore a soft deleted issue.
     */
    public function restore($id)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'plan_supervisor'])) {
            abort(403);
        }

        $issue = Issue::onlyTrashed()->findOrFail($id);
        $issue->restore();

        return redirect()->route('issues.index')
            ->with('success', 'تم استعادة المشكلة بنجاح.');
    }

    /**
     * Permanently delete an issue.
     */
    public function forceDelete($id)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $issue = Issue::onlyTrashed()->findOrFail($id);
        $issue->forceDelete();

        return redirect()->route('issues.index')
            ->with('success', 'تم حذف المشكلة نهائياً بنجاح.');
    }
}
