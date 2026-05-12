<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@jobyaari.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@jobyaari.com',
                'password' => Hash::make('password'),
            ]
        );

        // Categories
        $cats = [
            'Admit Card', 'Result', 'Sarkari Job', 'Engineering',
            'Arts', 'Science', 'Commerce', 'Education',
            'Pharmacy', 'Medical', 'Law', 'Management',
        ];

        foreach ($cats as $name) {
            Category::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        // Sample blogs
        $categories = Category::all();

        $blogs = [
            [
                'title'             => 'Central Bank Of India AGM Recruitment 2026 Notification Out – Apply Online',
                'short_description' => 'Central Bank of India AGM Recruitment 2026 Notification Out. Check eligibility, vacancy details, salary, selection process, and apply online before the last date.',
                'content'           => '<h2>Central Bank of India AGM Recruitment 2026</h2><p>Central Bank of India has released the official notification for AGM Recruitment 2026. Candidates who are interested and eligible can apply online through the official website.</p><h3>Important Dates</h3><table><thead><tr><th>Event</th><th>Date</th></tr></thead><tbody><tr><td>Notification Released</td><td>01 May 2026</td></tr><tr><td>Last Date to Apply</td><td>21 May 2026</td></tr><tr><td>Exam Date</td><td>June 2026 (Tentative)</td></tr></tbody></table><h3>Vacancy Details</h3><p>A total of <strong>286 vacancies</strong> are available across various posts.</p><h3>Eligibility Criteria</h3><ul><li>Graduate/Post Graduate degree from a recognized university</li><li>Age limit: 21-35 years</li></ul><h3>How to Apply</h3><ol><li>Visit the official website</li><li>Click on the recruitment notification</li><li>Fill in the application form</li><li>Pay the application fee</li><li>Submit and save the confirmation</li></ol>',
                'category'          => 'Sarkari Job',
                'tags'              => ['bank job', 'agm recruitment', '2026'],
            ],
            [
                'title'             => 'MPSC Civil Judge Recruitment 2026 Notification Out – Apply Online For 286 Posts',
                'short_description' => 'The Maharashtra Public Service Commission has released the MPSC Civil Judge Recruitment 2026 notification for 286 vacancies. Apply online from 01 May to 21 May 2026.',
                'content'           => '<h2>MPSC Civil Judge Recruitment 2026</h2><p>The Maharashtra Public Service Commission (MPSC) has officially released the Civil Judge Recruitment 2026 notification. This is a great opportunity for law graduates to secure a prestigious government job.</p><h3>Vacancy Details</h3><p>Total Posts: <strong>286</strong></p><ul><li>Civil Judge Junior Division: 250</li><li>Judicial Magistrate First Class: 36</li></ul><h3>Eligibility</h3><ul><li>LLB degree from a recognized institution</li><li>Age: 21 – 35 years (relaxation as per rules)</li></ul><h3>Selection Process</h3><ol><li>Preliminary Examination</li><li>Main Examination</li><li>Interview</li></ol>',
                'category'          => 'Law',
                'tags'              => ['mpsc', 'civil judge', 'maharashtra', '2026'],
            ],
            [
                'title'             => 'SSC CGL 2026 Notification Released – Check Eligibility & Apply Now',
                'short_description' => 'Staff Selection Commission Combined Graduate Level 2026 notification is out. Check posts, eligibility, syllabus, and apply online.',
                'content'           => '<h2>SSC CGL 2026 Notification</h2><p>Staff Selection Commission has released the CGL 2026 notification with thousands of vacancies across multiple departments.</p><h3>Posts Available</h3><ul><li>Assistant Audit Officer</li><li>Assistant Accounts Officer</li><li>Sub Inspector in CBI</li><li>Inspector (Central Excise)</li></ul><h3>Eligibility</h3><p>Bachelor\'s degree in any discipline from a recognized university.</p>',
                'category'          => 'Sarkari Job',
                'tags'              => ['ssc cgl', 'sarkari job', '2026'],
            ],
        ];

        foreach ($blogs as $data) {
            $cat = $categories->firstWhere('name', $data['category']) ?? $categories->first();
            $slug = Str::slug($data['title']);
            Blog::updateOrCreate(['slug' => $slug], [
                'title'             => $data['title'],
                'slug'              => $slug,
                'short_description' => $data['short_description'],
                'content'           => $data['content'],
                'category_id'       => $cat->id,
                'tags'              => $data['tags'],
                'published_at'      => now()->toDateString(),
            ]);
        }
    }
}
