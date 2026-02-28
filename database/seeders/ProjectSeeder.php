<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users to create projects for
        $users = User::all();

        foreach ($users as $user) {
            // Create sample projects for each user
            $projects = [
                [
                    'name' => 'Website Redesign',
                    'description' => 'Complete overhaul of the company website with modern design',
                    'status' => 'active',
                ],
                [
                    'name' => 'Mobile App Development',
                    'description' => 'Build a new mobile application for iOS and Android',
                    'status' => 'active',
                ],
                [
                    'name' => 'Marketing Campaign',
                    'description' => 'Q2 marketing campaign planning and execution',
                    'status' => 'completed',
                ],
            ];

            foreach ($projects as $project) {
                Project::create([
                    'user_id' => $user->id,
                    'name' => $project['name'],
                    'description' => $project['description'],
                    'status' => $project['status'],
                ]);
            }
        }
    }
}

