<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'title' => 'PHP Workshop',
                'description' => 'Learn Laravel basics',
                'type_id' => 1,
                'location' => 'Room 1',
                'date' => '2026-06-01',
                'time' => '10:00:00',
                'end_time' => '14:00:00',
                'max_participants' => 50,
                'points' => 10
            ],
            [
                'title' => 'Robotics Competition',
                'description' => 'Programming competition',
                'type_id' => 2,
                'location' => 'Lab',
                'date' => '2026-06-15',
                'time' => '09:00:00',
                'end_time' => '16:00:00',
                'max_participants' => 30,
                'points' => 20
            ],
            [
                'title' => 'AI Conference',
                'description' => 'Machine Learning introduction',
                'type_id' => 3,
                'location' => 'Hall 3',
                'date' => '2026-07-01',
                'time' => '11:00:00',
                'end_time' => '15:00:00',
                'max_participants' => 100,
                'points' => 15
            ],
            [
                'title' => 'Web Development Workshop',
                'description' => 'React and Node.js basics',
                'type_id' => 1,
                'location' => 'Lab 3',
                'date' => '2026-06-12',
                'time' => '10:00:00',
                'end_time' => '14:00:00',
                'max_participants' => 35,
                'points' => 16
            ],
            [
                'title' => 'Field Trip',
                'description' => 'Factory visit',
                'type_id' => 4,
                'location' => 'Industrial Area',
                'date' => '2026-06-25',
                'time' => '08:00:00',
                'end_time' => '14:00:00',
                'max_participants' => 40,
                'points' => 8
            ],
            [
                'title' => 'Cybersecurity Conference',
                'description' => 'Data and systems protection',
                'type_id' => 3,
                'location' => 'Main Conference Hall',
                'date' => '2026-07-15',
                'time' => '10:00:00',
                'end_time' => '16:00:00',
                'max_participants' => 150,
                'points' => 20
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Building Flutter applications',
                'type_id' => 1,
                'location' => 'Lab 1',
                'date' => '2026-06-22',
                'time' => '09:00:00',
                'end_time' => '13:00:00',
                'max_participants' => 30,
                'points' => 15
            ],
            [
                'title' => 'Volunteer Trip',
                'description' => 'Community volunteer work',
                'type_id' => 4,
                'location' => 'City Center',
                'date' => '2026-06-28',
                'time' => '08:00:00',
                'end_time' => '12:00:00',
                'max_participants' => 35,
                'points' => 10
            ],
            [
                'title' => 'Hackathon Competition',
                'description' => '48 hours of continuous programming',
                'type_id' => 2,
                'location' => 'University Campus',
                'date' => '2026-07-20',
                'time' => '08:00:00',
                'end_time' => '20:00:00',
                'max_participants' => 80,
                'points' => 30
            ],
            [
                'title' => 'Graphic Design Workshop',
                'description' => 'Learn Photoshop and Illustrator',
                'type_id' => 1,
                'location' => 'Computer Lab 2',
                'date' => '2026-06-10',
                'time' => '10:00:00',
                'end_time' => '13:00:00',
                'max_participants' => 25,
                'points' => 12
            ],
        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }
}