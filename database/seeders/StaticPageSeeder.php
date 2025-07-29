<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaticPage::insert([
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<p>About us content.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<p>Privacy policy content.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<p>Terms of service content.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
