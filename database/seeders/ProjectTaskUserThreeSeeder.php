<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectTaskUserThreeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create projects for user_id = 3
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

        $projectIds = [];

        foreach ($projects as $project) {
            $createdProject = Project::create([
                'user_id' => 3,
                'name' => $project['name'],
                'description' => $project['description'],
                'status' => $project['status'],
            ]);
            $projectIds[] = $createdProject->id;
        }

        // Create tasks for user_id = 3
        $tasks = [
            // Tasks with project_id
            [
                'title' => 'Design homepage mockup',
                'description' => 'Create initial design concepts for the new homepage',
                'category' => 'work',
                'priority' => 'high',
                'due_date' => now()->addDays(7),
                'status' => 'pending',
                'project_id' => $projectIds[0],
                'is_single_task' => false,
            ],
            [
                'title' => 'Implement responsive navigation',
                'description' => 'Build responsive navigation menu for all screen sizes',
                'category' => 'work',
                'priority' => 'medium',
                'due_date' => now()->addDays(14),
                'status' => 'pending',
                'project_id' => $projectIds[0],
                'is_single_task' => false,
            ],
            [
                'title' => 'Setup iOS project structure',
                'description' => 'Initialize Xcode project with proper architecture',
                'category' => 'work',
                'priority' => 'high',
                'due_date' => now()->addDays(5),
                'status' => 'pending',
                'project_id' => $projectIds[1],
                'is_single_task' => false,
            ],
            [
                'title' => 'Create Android wireframes',
                'description' => 'Design wireframes for main app screens',
                'category' => 'work',
                'priority' => 'medium',
                'due_date' => now()->addDays(10),
                'status' => 'pending',
                'project_id' => $projectIds[1],
                'is_single_task' => false,
            ],
            [
                'title' => 'Plan Q2 marketing strategy',
                'description' => 'Outline marketing goals and tactics for Q2',
                'category' => 'work',
                'priority' => 'high',
                'due_date' => now()->addDays(3),
                'status' => 'completed',
                'project_id' => $projectIds[2],
                'is_single_task' => false,
            ],
            // Tasks without project_id (single tasks)
            [
                'title' => 'Review quarterly reports',
                'description' => 'Review and summarize Q1 performance reports',
                'category' => 'work',
                'priority' => 'medium',
                'due_date' => now()->addDays(2),
                'status' => 'pending',
                'project_id' => null,
                'is_single_task' => true,
            ],
            [
                'title' => 'Gym workout',
                'description' => 'Cardio and strength training session',
                'category' => 'health',
                'priority' => 'high',
                'due_date' => now()->addDays(1),
                'status' => 'pending',
                'project_id' => null,
                'is_single_task' => true,
            ],
            [
                'title' => 'Read programming book',
                'description' => 'Continue reading "Clean Code" chapter 5',
                'category' => 'learning',
                'priority' => 'low',
                'due_date' => now()->addDays(7),
                'status' => 'pending',
                'project_id' => null,
                'is_single_task' => true,
            ],
            [
                'title' => 'Buy groceries',
                'description' => 'Weekly grocery shopping',
                'category' => 'personal',
                'priority' => 'medium',
                'due_date' => now()->addDays(1),
                'status' => 'pending',
                'project_id' => null,
                'is_single_task' => true,
            ],
            [
                'title' => 'Team meeting preparation',
                'description' => 'Prepare slides for weekly team standup',
                'category' => 'work',
                'priority' => 'low',
                'due_date' => now()->addDays(4),
                'status' => 'pending',
                'project_id' => null,
                'is_single_task' => true,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create([
                'user_id' => 3,
                'title' => $task['title'],
                'description' => $task['description'],
                'category' => $task['category'],
                'priority' => $task['priority'],
                'due_date' => $task['due_date'],
                'status' => $task['status'],
                'project_id' => $task['project_id'],
                'is_single_task' => $task['is_single_task'],
            ]);
        }
    }
}

