<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\AttendanceRecord;
use App\Models\Notification;

class BadgeService
{
    /**
     * فحص ومنح الشارات المؤهلة للطالب
     */
    public function checkAndAward(User $user): array
    {
        $awarded = [];
        $badges = Badge::where('is_active', true)->get();
        
        foreach ($badges as $badge) {
            // تحقق إذا الطالب عنده الشارة بالفعل
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }
            
            $qualified = false;
            
            switch ($badge->type) {
                case 'activities':
                    $qualified = $user->activities_completed >= $badge->requirement;
                    break;
                    
                case 'points':
                    $qualified = $user->total_points >= $badge->requirement;
                    break;
                    
                case 'attendance':
                    $count = AttendanceRecord::where('user_id', $user->id)
                        ->where('status', 'present')
                        ->count();
                    $qualified = $count >= $badge->requirement;
                    break;
                    
                case 'ratings':
                    $count = \DB::table('ratings')
                        ->where('user_id', $user->id)
                        ->count();
                    $qualified = $count >= $badge->requirement;
                    break;
            }
            
            if ($qualified) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                
                // إنشاء إشعار
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'badge_earned',
                    'title' => '🏆 مبارك! حصلت على شارة جديدة',
                    'message' => "حصلت على شارة \"{$badge->name}\" - {$badge->description}",
                    'icon' => $badge->icon ?? '🏆',
                    'color' => $badge->color ?? '#d4a017',
                    'is_read' => false,
                ]);
                
                $awarded[] = $badge->name;
            }
        }
        
        return $awarded;
    }
}