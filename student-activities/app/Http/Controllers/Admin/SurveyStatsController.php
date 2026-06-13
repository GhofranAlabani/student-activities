<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyStatsController extends Controller
{
    /**
     * عرض الإحصائيات العامة
     */
    public function index()
    {
        $totalQuestions = SurveyQuestion::count();
        $totalResponses = SurveyResponse::count();
        $totalActivities = Activity::count();
        $totalStudents = SurveyResponse::distinct('user_id')->count('user_id');
        
        // نسبة المشاركة
        $participationRate = $totalActivities > 0 ? round(($totalResponses / ($totalActivities * $totalQuestions)) * 100, 2) : 0;
        
        // توزيع الإجابات العام
        $answersDistribution = SurveyResponse::select('answer', DB::raw('COUNT(*) as count'))
            ->groupBy('answer')
            ->get();
        
        // الإجابات حسب كل سؤال
        $questionsWithAnswers = SurveyQuestion::with(['responses' => function($query) {
            $query->select('question_id', 'answer', DB::raw('COUNT(*) as count'))
                  ->groupBy('question_id', 'answer');
        }])
        ->get();
        
        return view('admin.survey-stats.index', compact(
            'totalQuestions',
            'totalResponses',
            'totalActivities',
            'totalStudents',
            'participationRate',
            'answersDistribution',
            'questionsWithAnswers'
        ));
    }
    
    /**
     * إحصائيات نشاط معين
     */
    public function activityStats($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        
        $totalResponses = SurveyResponse::where('activity_id', $activityId)->count();
        $totalStudents = SurveyResponse::where('activity_id', $activityId)
            ->distinct('user_id')
            ->count('user_id');
        
        $totalQuestions = SurveyQuestion::count();
        
        // نسبة المشاركة في النشاط
        $participationRate = $totalQuestions > 0 ? round(($totalResponses / ($totalQuestions * $totalStudents)) * 100, 2) : 0;
        
        $questionsStats = SurveyQuestion::with(['responses' => function($query) use ($activityId) {
            $query->where('activity_id', $activityId)
                  ->select('question_id', 'answer', DB::raw('COUNT(*) as count'))
                  ->groupBy('question_id', 'answer');
        }])
        ->get();
        
        $answersDistribution = SurveyResponse::where('activity_id', $activityId)
            ->select('answer', DB::raw('COUNT(*) as count'))
            ->groupBy('answer')
            ->get();
        
        return view('admin.survey-stats.activity', compact(
            'activity',
            'totalResponses',
            'totalStudents',
            'totalQuestions',
            'participationRate',
            'questionsStats',
            'answersDistribution'
        ));
    }
}