<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserDummySeeder extends Seeder
{
    /**
     * Seed a dummy regular user with 3 projects and 20 tasks.
     * - 10 tasks are standalone (no project / single tasks)
     * - 10 tasks are distributed across 3 projects
     */
    public function run(): void
    {
        // ── Create (or find) the dummy user ──────────────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'     => 'User Demo',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        // ── 3 Projects ───────────────────────────────────────────────────────
        $projects = [
            Project::create([
                'user_id'     => $user->id,
                'name'        => 'Website Redesign',
                'description' => 'Merombak tampilan website utama perusahaan menjadi lebih modern dan responsif.',
                'status'      => 'active',
            ]),
            Project::create([
                'user_id'     => $user->id,
                'name'        => 'Mobile App Development',
                'description' => 'Membangun aplikasi mobile cross-platform untuk pelanggan B2C.',
                'status'      => 'active',
            ]),
            Project::create([
                'user_id'     => $user->id,
                'name'        => 'Database Migration',
                'description' => 'Migrasi database legacy ke PostgreSQL dengan zero downtime.',
                'status'      => 'active',
            ]),
        ];

        // ── 10 Tasks in Projects (≈3-4 per project) ──────────────────────────
        $projectTasks = [
            // Project 0 – Website Redesign (4 tasks)
            ['project' => 0, 'title' => 'Buat wireframe halaman utama',     'category' => 'work',     'priority' => 'high',   'status' => 'completed', 'due_date' => now()->subDays(5)],
            ['project' => 0, 'title' => 'Desain komponen UI library',       'category' => 'work',     'priority' => 'high',   'status' => 'in-progress','due_date' => now()->addDays(3)],
            ['project' => 0, 'title' => 'Implementasi halaman landing',      'category' => 'work',     'priority' => 'medium', 'status' => 'pending',   'due_date' => now()->addDays(7)],
            ['project' => 0, 'title' => 'Review & QA desain mobile',        'category' => 'work',     'priority' => 'low',    'status' => 'pending',   'due_date' => now()->addDays(12)],
            // Project 1 – Mobile App (3 tasks)
            ['project' => 1, 'title' => 'Setup project React Native',       'category' => 'work',     'priority' => 'high',   'status' => 'completed', 'due_date' => now()->subDays(10)],
            ['project' => 1, 'title' => 'Integrasi REST API authentication', 'category' => 'work',    'priority' => 'high',   'status' => 'in-progress','due_date' => now()->addDays(2)],
            ['project' => 1, 'title' => 'Testing unit push notification',   'category' => 'work',     'priority' => 'medium', 'status' => 'pending',   'due_date' => now()->addDays(9)],
            // Project 2 – Database Migration (3 tasks)
            ['project' => 2, 'title' => 'Audit skema database lama',        'category' => 'work',     'priority' => 'high',   'status' => 'completed', 'due_date' => now()->subDays(7)],
            ['project' => 2, 'title' => 'Tulis skrip migrasi tabel user',   'category' => 'work',     'priority' => 'high',   'status' => 'in-progress','due_date' => now()->addDays(1)],
            ['project' => 2, 'title' => 'Validasi data post-migrasi',       'category' => 'work',     'priority' => 'medium', 'status' => 'pending',   'due_date' => now()->addDays(14)],
        ];

        foreach ($projectTasks as $taskData) {
            Task::create([
                'user_id'       => $user->id,
                'project_id'    => $projects[$taskData['project']]->id,
                'title'         => $taskData['title'],
                'category'      => $taskData['category'],
                'priority'      => $taskData['priority'],
                'status'        => $taskData['status'],
                'due_date'      => $taskData['due_date'],
                'is_single_task'=> false,
            ]);
        }

        // ── 10 Single Tasks (no project) ─────────────────────────────────────
        $singleTasks = [
            ['title' => 'Baca buku "Clean Code" bab 5-6',     'category' => 'learning',  'priority' => 'medium', 'status' => 'completed',   'due_date' => now()->subDays(3)],
            ['title' => 'Olahraga pagi 30 menit',             'category' => 'health',    'priority' => 'low',    'status' => 'completed',   'due_date' => now()->subDays(1)],
            ['title' => 'Kirim laporan mingguan ke manager',  'category' => 'work',      'priority' => 'high',   'status' => 'completed',   'due_date' => now()->subDays(2)],
            ['title' => 'Pelajari dasar-dasar Docker',        'category' => 'learning',  'priority' => 'medium', 'status' => 'in-progress', 'due_date' => now()->addDays(4)],
            ['title' => 'Rencanakan budget bulanan',          'category' => 'personal',  'priority' => 'medium', 'status' => 'in-progress', 'due_date' => now()->addDays(2)],
            ['title' => 'Update CV & LinkedIn profile',       'category' => 'personal',  'priority' => 'low',    'status' => 'pending',     'due_date' => now()->addDays(5)],
            ['title' => 'Ikuti kursus online TypeScript',     'category' => 'learning',  'priority' => 'medium', 'status' => 'pending',     'due_date' => now()->addDays(10)],
            ['title' => 'Check-up kesehatan tahunan',         'category' => 'health',    'priority' => 'high',   'status' => 'pending',     'due_date' => now()->addDays(1)],
            ['title' => 'Balas semua email client',           'category' => 'work',      'priority' => 'high',   'status' => 'pending',     'due_date' => now()->addDays(0)],
            ['title' => 'Bersihkan dan rapikan meja kerja',   'category' => 'personal',  'priority' => 'low',    'status' => 'pending',     'due_date' => now()->addDays(6)],
        ];

        foreach ($singleTasks as $taskData) {
            Task::create([
                'user_id'        => $user->id,
                'project_id'     => null,
                'title'          => $taskData['title'],
                'category'       => $taskData['category'],
                'priority'       => $taskData['priority'],
                'status'         => $taskData['status'],
                'due_date'       => $taskData['due_date'],
                'is_single_task' => true,
            ]);
        }

        $this->command->info("✅ Dummy user created: user@gmail.com / password");
        $this->command->info("   → 3 projects, 10 project tasks, 10 single tasks");
    }
}
