<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyResponseController extends Controller
{
    public function show($activityId)
   {
    $activity = Activity::findOrFail($activityId);
    $questions = SurveyQuestion::latest()->get();
    
    return view('student.survey.show', compact('activity', 'questions'));
   }

    public function submit(Request $request, $activityId)
    {
        $questions = SurveyQuestion::all();
        
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:موافق,محايد,لا أوافق ',
        ]);

        // حفظ كل إجابة
        foreach ($questions as $question) {
            $answer = $validated['answers'][$question->id] ?? null;
            
            if ($answer) {
                SurveyResponse::create([
                    'user_id' => Auth::id(),
                    'activity_id' => $activityId,
                    'question_id' => $question->id,
                    'answer' => $answer,
                ]);
            }
        }

        return redirect()->route('activities.show', $activityId)
            ->with('success', 'شكراً لمشاركتك! تم إرسال إجاباتك بنجاح.');
    }
}