<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Lesson\Models\Lesson;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing teachers (users with 'teacher' role)
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get();

        // Get existing students (users with 'student' role)
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->get();

        if ($teachers->isEmpty() || $students->isEmpty()) {
            $this->command->warn('Teachers or students not found. Ensure AuthTableSeeder and UserSeeder run first.');
            return;
        }

        // Get teacher IDs for lesson assignment
        $teacherIds = $teachers->pluck('id')->toArray();
        $studentIds = $students->pluck('id')->toArray();

        // Lesson data: title, description, content, instrument
        $lessonsData = [
            [
                'title' => 'Piano Basics for Beginners',
                'description' => 'Learn the fundamentals of piano playing',
                'content' => 'In this comprehensive lesson, you will learn: proper hand positioning, basic music theory, and fundamental piano techniques.',
                'status' => 'published',
                'instrument' => 'piano',
            ],
            [
                'title' => 'Vocal Training Techniques',
                'description' => 'Master your voice with professional techniques',
                'content' => 'This lesson covers breathing techniques, vocal exercises, pitch control, and stage presence for singers.',
                'status' => 'published',
                'instrument' => 'vocals',
            ],
            [
                'title' => 'Guitar Fundamentals',
                'description' => 'Introduction to acoustic guitar',
                'content' => 'Start your guitar journey with proper grip, basic chords, strumming patterns, and your first songs.',
                'status' => 'published',
                'instrument' => 'guitar',
            ],
            [
                'title' => 'Music Theory Basics',
                'description' => 'Understanding scales, chords, and composition',
                'content' => 'Explore music theory concepts including scales, intervals, chord progressions, and basic composition.',
                'status' => 'draft',
                'instrument' => null,
            ],
            [
                'title' => 'Rhythm and Percussion',
                'description' => 'Developing your sense of rhythm and timing',
                'content' => 'Learn rhythm patterns, percussion basics, and how to maintain perfect timing in ensemble settings.',
                'status' => 'archived',
                'instrument' => 'percussion',
            ],
        ];

        foreach ($lessonsData as $index => $data) {
            $lesson = Lesson::updateOrCreate(
                ['title' => $data['title']],
                [
                    'slug' => str()->slug($data['title']),
                    'description' => $data['description'],
                    'content' => $data['content'],
                    'status' => $data['status'],
                    'published_at' => $data['status'] === 'published' ? now() : null,
                    'teacher_id' => $teacherIds[$index % count($teacherIds)],
                    'instrument' => $data['instrument'],
                    'order' => $index + 1,
                ]
            );

            $assignedCount = min(2, count($studentIds));
            $assignedStudents = array_slice($studentIds, 0, $assignedCount);
            $lesson->students()->sync($assignedStudents);
        }

        $this->command->info('Lessons seeded successfully!');
    }
}
