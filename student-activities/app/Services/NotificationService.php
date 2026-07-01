<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * إنشاء إشعار جديد
     */
    public function create(User $user, string $type, string $title, string $message, string $icon = '🔔', string $color = '#3b82f6', $actionUrl = null, $data = null)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'action_url' => $actionUrl,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    /**
     * إشعار عند كسب شارة
     */
    public function badgeEarned(User $user, $badge)
    {
        return $this->create(
            $user,
            'badge_earned',
            ' مبروك! حصلت على شارة جديدة',
            "لقد حصلت على شارة \"{$badge->name}\" - {$badge->description}",
            $badge->icon ?? '🏆',
            $badge->color ?? '#d4a017',
            route('student.profile'),
            ['badge_id' => $badge->id, 'badge_name' => $badge->name]
        );
    }

    /**
     * إشعار عند كسب نقاط
     */
    public function pointsEarned(User $user, int $points, string $reason, $activity = null)
    {
        return $this->create(
            $user,
            'points_earned',
            '⭐ نقاط جديدة!',
            "حصلت على {$points} نقطة - {$reason}",
            '⭐',
            '#f59e0b',
            $activity ? route('activities.show', $activity->id) : route('student.profile'),
            ['points' => $points, 'reason' => $reason, 'total_points' => $user->total_points]
        );
    }

    /**
     * إشعار عند التسجيل في نشاط
     */
    public function activityRegistered(User $user, $activity)
    {
        return $this->create(
            $user,
            'activity_registered',
            '✅ تم التسجيل بنجاح',
            "تم تسجيلك في النشاط: {$activity->title}",
            '',
            '#10b981',
            route('activities.show', $activity->id),
            ['activity_id' => $activity->id, 'activity_title' => $activity->title]
        );
    }

    /**
     * إشعار عند الحضور
     */
    public function attendanceMarked(User $user, $activity, int $points)
    {
        return $this->create(
            $user,
            'attendance_marked',
            '✅ تم تسجيل الحضور',
            "تم تسجيل حضورك في \"{$activity->title}\" وحصلت على {$points} نقطة",
            '✅',
            '#3b82f6',
            route('student.profile'),
            ['activity_id' => $activity->id, 'points' => $points]
        );
    }

    /**
     * إشعار عند الوصول لمستوى جديد
     */
    public function levelUp(User $user, string $newLevel)
    {
        return $this->create(
            $user,
            'level_up',
            '🎉 مبروك! وصلت لمستوى جديد',
            "لقد وصلت لمستوى \"{$newLevel}\" - استمر في التألق!",
            '🎉',
            '#9333ea',
            route('student.profile'),
            ['level' => $newLevel]
        );
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * الحصول على الإشعارات
     */
    public function getNotifications(User $user, int $limit = 10)
    {
        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * تحديد كل الإشعارات كمقروءة
     */
    public function markAllAsRead(User $user)
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}