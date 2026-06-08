<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    // عرض الاستبيان للطالب
    public function show($activityId)
    {
        $survey = Survey::with('questions')
            ->where('activity_id', $activityId)
            ->where('is_active', true)
            ->firstOrFail();

        // التحقق إذا كان الطالب قد أجاب مسبقاً
        $alreadyResponded = $survey->hasUserResponded(Auth::id());

        if ($alreadyResponded) {
            return redirect()->route('activities.index')
                ->with('info', 'لقد قمت بالإجابة على هذا الاستبيان مسبقاً');
        }

        return view('student.surveys.show', compact('survey'));
    }

    // حفظ إجابات الطالب
    public function submit(Request $request, $activityId)
    {
        $survey = Survey::with('questions')
            ->where('activity_id', $activityId)
            ->firstOrFail();

        $answers = [];
        $validationRules = [];

        foreach ($survey->questions as $question) {
            if ($question->required) {
                $validationRules["question_{$question->id}"] = 'required';
            }

            $answers[$question->id] = $request->input("question_{$question->id}");
        }

        $request->validate($validationRules);

        SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => Auth::id(),
            'activity_id' => $activityId,
            'answers' => $answers,
        ]);

        return redirect()->route('activities.index')
            ->with('success', 'تم إرسال إجاباتك بنجاح، شكراً لمشاركتك!');
    }
}