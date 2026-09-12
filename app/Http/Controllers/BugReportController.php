<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\User;
use App\Notifications\BugReportSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BugReportController extends Controller
{
    public function create()
    {
        return view('bug-report.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:5000',
            'page' => 'nullable|string|max:500',
        ]);

        $report = BugReport::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'page' => $validated['page'] ?? $request->headers->get('referer'),
            'browser' => $this->parseBrowser($request->userAgent()),
            'status' => BugReport::STATUS_REPORTED,
        ]);

        $this->notifyAdmins($report);

        return redirect()->route('bug-reports.create')
            ->with('flash_success', 'Thank you! Your bug report has been submitted and we\'ll take a look.');
    }

    public function index(Request $request)
    {
        $query = BugReport::with('user:id,name')->latest();

        if (in_array($request->get('status'), BugReport::STATUSES, true)) {
            $query->where('status', $request->get('status'));
        }

        $reports = $query->paginate(15)->appends($request->all());

        $counts = [
            'all' => BugReport::count(),
            BugReport::STATUS_REPORTED => BugReport::where('status', BugReport::STATUS_REPORTED)->count(),
            BugReport::STATUS_IN_PROGRESS => BugReport::where('status', BugReport::STATUS_IN_PROGRESS)->count(),
            BugReport::STATUS_RESOLVED => BugReport::where('status', BugReport::STATUS_RESOLVED)->count(),
            BugReport::STATUS_WONT_FIX => BugReport::where('status', BugReport::STATUS_WONT_FIX)->count(),
        ];

        return view('admin.bug-reports.index', compact('reports', 'counts'));
    }

    public function show(BugReport $report)
    {
        $report->load(['user:id,name,email', 'handler:id,name']);

        return view('admin.bug-reports.show', compact('report'));
    }

    public function update(Request $request, BugReport $report)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', BugReport::STATUSES)],
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $report->status = $validated['status'];
        $report->admin_notes = $validated['admin_notes'] ?? null;
        $report->handled_by = Auth::id();
        $report->resolved_at = in_array($validated['status'], [BugReport::STATUS_RESOLVED, BugReport::STATUS_WONT_FIX], true)
            ? now()
            : null;
        $report->save();

        return redirect()->route('admin.bug-reports.index')
            ->with('status', 'Bug report updated successfully.');
    }

    private function notifyAdmins(BugReport $report): void
    {
        $admins = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['super admin', 'administrator']))->get();

        foreach ($admins as $admin) {
            $admin->notify(new BugReportSubmittedNotification($report));
        }
    }

    private function parseBrowser(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown';
        }

        $browsers = [
            'Edg/' => 'Edge',
            'Firefox/' => 'Firefox',
            'Chrome/' => 'Chrome',
            'Safari/' => 'Safari',
            'Opera/' => 'Opera',
            'MSIE/' => 'Internet Explorer',
        ];

        foreach ($browsers as $key => $name) {
            if (str_contains($userAgent, $key)) {
                return $name;
            }
        }

        return 'Unknown';
    }
}