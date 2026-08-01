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
            ['name' => 'Vocals', 'description' => 'Voice training, breathing, and performance.'],
            ['name' => 'Percussion', 'description' => 'Rhythm studies and percussion technique.'],
            ['name' => 'Violin', 'description' => 'String fundamentals and musical expression.'],
            ['name' => 'Drums', 'description' => 'Drum kit coordination and groove development.'],
            ['name' => 'Steel Pan', 'description' => 'Steel pan technique, tone control, and repertoire.'],
        ];

        foreach ($defaultInstruments as $instrumentData) {
            Instrument::query()->updateOrCreate(
                ['name' => $instrumentData['name']],
                [
                    'description' => $instrumentData['description'],
                    'is_active' => true,
                ]
            );
        }

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