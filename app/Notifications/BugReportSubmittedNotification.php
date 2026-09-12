<?php

namespace App\Notifications;

use App\Models\BugReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BugReportSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly BugReport $report)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $reporterName = $this->report->user?->name ?? 'A user';

        return [
            'type' => 'bug_report',
            'title' => 'New Bug Report',
            'message' => "{$reporterName} reported: \"{$this->report->title}\".",
            'bug_report_id' => $this->report->id,
            'url' => route('admin.bug-reports.show', $this->report),
        ];
    }
}