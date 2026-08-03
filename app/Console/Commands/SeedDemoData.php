<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-demo
                            {--fresh : Run migrate:fresh before seeding demo data}
                            {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed deterministic demo data (admins, teachers, students, lessons, and module dummy data)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('fresh')) {
            if (app()->environment('production') && ! $this->option('force')) {
                $this->error('This command will drop all tables. Use --force to run in production.');

                return self::FAILURE;
            }

            $this->info('Running fresh migrations...');
            $this->call('migrate:fresh', [
                '--force' => $this->option('force'),
            ]);
        }

        putenv('SEED_DUMMY_DATA=true');
        $_ENV['SEED_DUMMY_DATA'] = 'true';
        $_SERVER['SEED_DUMMY_DATA'] = 'true';

        $this->call('config:clear');

        $this->info('Seeding deterministic demo data...');
        $this->call('db:seed', [
            '--class' => 'Database\\Seeders\\DatabaseSeeder',
            '--force' => $this->option('force'),
            '--no-interaction' => true,
        ]);

        $this->newLine();
        $this->info('Demo seeding completed.');
        $this->line('Login credentials (all password: <fg=green>password</fg=green>):');
        $this->line('- super@admin.com');
        $this->line('- admin@admin.com');
        $this->line('- teacher1@example.com, teacher2@example.com');
        $this->line('- student1@example.com, student2@example.com, student3@example.com');

        return self::SUCCESS;
    }
}
