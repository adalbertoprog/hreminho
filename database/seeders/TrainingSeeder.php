<?php

namespace Database\Seeders;

use App\Models\Training;
use Illuminate\Database\Seeder;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        $trainings = [
            ['title' => 'Laravel Advanced Techniques',    'provider' => 'Udemy',             'description' => 'Deep dive into advanced Laravel features.'],
            ['title' => 'Leadership & Management',         'provider' => 'Coursera',          'description' => 'Develop skills to lead teams effectively.'],
            ['title' => 'Data Analysis with Python',       'provider' => 'LinkedIn Learning', 'description' => 'Learn data wrangling and visualization with Python.'],
            ['title' => 'Communication Skills',            'provider' => 'Internal Training', 'description' => 'Improve verbal and written communication in the workplace.'],
            ['title' => 'Cybersecurity Fundamentals',      'provider' => 'Coursera',          'description' => 'Introduction to cybersecurity best practices.'],
            ['title' => 'Project Management (PMP)',        'provider' => 'FGV',               'description' => 'Preparation for PMP certification.'],
            ['title' => 'Excel for Finance',               'provider' => 'Alura',             'description' => 'Advanced Excel formulas and dashboards for finance.'],
            ['title' => 'UX Design Principles',            'provider' => 'Udemy',             'description' => 'User experience design fundamentals and methodologies.'],
            ['title' => 'DevOps & CI/CD',                  'provider' => 'LinkedIn Learning', 'description' => 'Set up automated pipelines and deployment workflows.'],
            ['title' => 'Agile & Scrum',                   'provider' => 'Internal Training', 'description' => 'Agile methodology applied to software development teams.'],
        ];

        foreach ($trainings as $training) {
            Training::firstOrCreate(['title' => $training['title']], $training);
        }
    }
}
