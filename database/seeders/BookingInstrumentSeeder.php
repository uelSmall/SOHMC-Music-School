<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Booking\Models\Instrument;

class BookingInstrumentSeeder extends Seeder
{
    public function run(): void
    {
        $defaultInstruments = [
            ['name' => 'Piano', 'description' => 'Keyboard technique, sight reading, and repertoire.'],
            ['name' => 'Guitar', 'description' => 'Acoustic and electric guitar fundamentals.'],
            ['name' => 'Saxophone', 'description' => 'Tone production, breath control, and melodic phrasing.'],
            ['name' => 'Voice / Singing', 'description' => 'Voice training, breathing, and performance.'],
            ['name' => 'Violin', 'description' => 'String fundamentals and musical expression.'],
            ['name' => 'Keyboard', 'description' => 'Modern keyboard skills, chords, voicing, and accompaniment.'],
            ['name' => 'Steelpan', 'description' => 'Steelpan technique, tone control, and repertoire.'],
            ['name' => 'Music Theory', 'description' => 'Foundational theory, notation, harmony, and ear training.'],
        ];

        $defaultInstrumentNames = collect($defaultInstruments)
            ->pluck('name')
            ->all();

        foreach ($defaultInstruments as $instrumentData) {
            Instrument::query()->updateOrCreate(
                ['name' => $instrumentData['name']],
                [
                    'description' => $instrumentData['description'],
                    'is_active' => true,
                ]
            );
        }

        Instrument::query()
            ->whereNotIn('name', $defaultInstrumentNames)
            ->update(['is_active' => false]);

        $instrumentIds = Instrument::query()->active()->pluck('id');

        if ($instrumentIds->isEmpty()) {
            return;
        }

        $teachers = User::role('teacher')->get();

        foreach ($teachers as $teacher) {
            if ($teacher->teachingInstruments()->count() === 0) {
                $teacher->teachingInstruments()->syncWithoutDetaching($instrumentIds);
            }
        }
    }
}