<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Lesson\Enums\AssignmentStatus;
use Modules\Lesson\Models\LessonAssignmentComment;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Models\LessonStudentAssignment;
use Throwable;

class ParentStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parent = User::where('email', 'parent1@example.com')->first();

        if (! $parent) {
            return;
        }

        $studentIds = User::query()
            ->whereIn('email', ['student1@example.com', 'student2@example.com'])
            ->pluck('id')
            ->toArray();

        if (empty($studentIds)) {
            return;
        }

        $parent->children()->syncWithoutDetaching($studentIds);

        $lessons = Lesson::query()
            ->where('status', 'published')
            ->orderBy('order')
            ->take(4)
            ->get();

        if ($lessons->isEmpty()) {
            return;
        }

        $studentsByIndex = $studentIds;
        $assignmentSeedData = [
            [0, 0, AssignmentStatus::Started, now()->addDays(3)],
            [1, 0, AssignmentStatus::InProgress, now()->addDays(5)],
            [2, 1, AssignmentStatus::Assigned, now()->addDays(2)],
            [3, 1, AssignmentStatus::Completed, now()->addDays(9)],
        ];

        foreach ($assignmentSeedData as [$lessonIndex, $studentIndex, $status, $dueDate]) {
            $lesson = $lessons[$lessonIndex % $lessons->count()];
            $studentId = $studentsByIndex[$studentIndex] ?? $studentsByIndex[0];

            $assignment = LessonStudentAssignment::updateOrCreate(
                [
                    'lesson_id' => $lesson->id,
                    'student_id' => $studentId,
                ],
                [
                    'assigned_at' => now()->subDays(2),
                    'due_date' => $dueDate->toDateString(),
                    'status' => $status->value,
                ]
            );

            $child = User::find($studentId);

            if (! $child) {
                continue;
            }

            $notificationTitle = $status === AssignmentStatus::Completed
                ? 'Practice milestone reached'
                : 'New lesson assigned';

            $notificationMessage = match ($status) {
                AssignmentStatus::Completed => $child->name.' completed "'.$lesson->title.'".',
                AssignmentStatus::InProgress => $child->name.' is making progress on "'.$lesson->title.'".',
                AssignmentStatus::Started => $child->name.' started "'.$lesson->title.'".',
                default => 'A lesson was assigned to '.$child->name.'.',
            };

            try {
                $notificationType = 'parent_dashboard_update_'.Str::slug($lesson->title)."_{$studentId}";

                DB::table('notifications')
                    ->where('notifiable_type', User::class)
                    ->where('notifiable_id', $parent->id)
                    ->where('type', $notificationType)
                    ->delete();

                DB::table('notifications')->insert([
                    'id' => (string) Str::uuid(),
                    'type' => $notificationType,
                    'notifiable_type' => User::class,
                    'notifiable_id' => $parent->id,
                    'data' => json_encode([
                        'type' => 'parent_dashboard_update',
                        'title' => $notificationTitle,
                        'message' => $notificationMessage,
                        'status' => $assignment->status->value,
                        'lesson_id' => $lesson->id,
                        'assignment_id' => $assignment->id,
                        'url' => route('lessons.show', $lesson),
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                LessonAssignmentComment::query()
                    ->where('lesson_student_assignment_id', $assignment->id)
                    ->delete();

                LessonAssignmentComment::create([
                    'lesson_student_assignment_id' => $assignment->id,
                    'teacher_id' => $lesson->teacher_id,
                    'body' => match ($status) {
                        AssignmentStatus::Completed => 'Great work. Keep practicing the rhythm and tone for consistency.',
                        AssignmentStatus::InProgress => 'Nice progress. Focus on smooth transitions and steady tempo.',
                        AssignmentStatus::Started => 'Good start. Begin with slow practice and correct hand positioning.',
                        default => 'Review the lesson and try the suggested warm-up exercises.',
                    },
                ]);
            } catch (Throwable) {
                // Dummy notifications are best-effort only.
            }
        }
    }
}
