<?php

namespace Database\Seeders;

use App\Models\Video;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VideoPageSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $videos = [
            [
                'youtube_id' => 'x_q-gz_l5XM',
                'title' => 'Hands-on: flagship killer of the year?',
            ],
            [
                'youtube_id' => 'Hbx8bLFc0IU',
                'title' => 'Camera review and low-light test',
            ],
            [
                'youtube_id' => 'aIpxMkJd_V4',
                'title' => 'Battery endurance and charging deep dive',
            ],
            [
                'youtube_id' => 'BD_HmUCUDGA',
                'title' => 'Display, speakers and gaming performance',
            ],
            [
                'youtube_id' => '4uQIw98QeOI',
                'title' => 'Full review recap in under 10 minutes',
            ],
            [
                'youtube_id' => '43ABU-eEOJE',
                'title' => 'Best phones of the month – roundup',
            ],
            [
                'youtube_id' => 'VLtMPSbuX5g',
                'title' => 'Unboxing & first impressions',
            ],
            [
                'youtube_id' => 'jwr0LTp1QZ0',
                'title' => 'Software, features & long-term verdict',
            ],
        ];

        foreach ($videos as $v) {
            Video::updateOrCreate(
                ['youtube_id' => $v['youtube_id']], // Unique key
                [
                    'title' => $v['title'],
                    'slug' => Str::slug($v['title']),
                    'author_id' => $user->id,
                    'is_published' => true,
                    'published_at' => now(),
                    'description' => $v['title'] . ' - Detailed review and analysis.',
                ]
            );
        }
    }
}
