<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyResponseController extends Controller
{
    public function index()
    {
        $responses = DB::table('survey_responses')
            ->join('users', 'survey_responses.user_id', '=', 'users.id')
            ->join('activities', 'survey_responses.activity_id', '=', 'activities.id')
            ->select(
                'survey_responses.*',
                'users.name as student_name',
                'activities.title as activity_title'
            )
            ->latest('survey_responses.created_at')
            ->paginate(20);
        
        return view('admin.survey-responses.index', compact('responses'));
    }
    
    public function show($id)
    {
        $response = DB::table('survey_responses')
            ->join('users', 'survey_responses.user_id', '=', 'users.id')
            ->join('activities', 'survey_responses.activity_id', '=', 'activities.id')
            ->select(
                'survey_responses.*',
                'users.name as student_name',
                'users.email as student_email',
                'activities.title as activity_title'
            )
            ->where('survey_responses.id', $id)
            ->first();
        
        $answers = DB::table('survey_answers')
            ->join('survey_questions', 'survey_answers.question_id', '=', 'survey_questions.id')
            ->select('survey_questions.question', 'survey_questions.type', 'survey_answers.answer')
            ->where('survey_answers.response_id', $id)
            ->get();
        
        return view('admin.survey-responses.show', compact('response', 'answers'));
    }
}