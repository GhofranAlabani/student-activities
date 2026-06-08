<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    // عرض الاستبيانات
    public function index()
    {
        $surveys = Survey::with('activity')->latest()->get();
        return view('admin.surveys.index', compact('surveys'));
    }

    // إنشاء استبيان جديد
    public function create($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        return view('admin.surveys.create', compact('activity'));
    }

    // حفظ الاستبيان
    public function store(Request $request, $activityId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $survey = Survey::create([
            'activity_id' => $activityId,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.surveys.questions', $survey->id);
    }

    // إضافة أسئلة
    public function addQuestion(Request $request, $surveyId)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:text,radio,checkbox,rating',
            'options' => 'nullable|string',
            'required' => 'boolean',
        ]);

        $options = null;
        if ($request->options) {
            $options = explode("\n", $request->options);
        }

        SurveyQuestion::create([
            'survey_id' => $surveyId,
            'question' => $request->question,
            'type' => $request->type,
            'options' => $options,
            'required' => $request->has('required'),
            'order' => SurveyQuestion::where('survey_id', $surveyId)->count() + 1,
        ]);

        return back()->with('success', 'تمت إضافة السؤال بنجاح');
    }

    // حذف سؤال
    public function deleteQuestion($questionId)
    {
        SurveyQuestion::findOrFail($questionId)->delete();
        return back()->with('success', 'تم حذف السؤال');
    }

    // عرض الاستبيان مع الأسئلة
    public function show($surveyId)
    {
        $survey = Survey::with('questions')->findOrFail($surveyId);
        return view('admin.surveys.show', compact('survey'));
    }

    // حذف استبيان
    public function destroy($surveyId)
    {
        Survey::findOrFail($surveyId)->delete();
        return redirect()->route('admin.surveys.index')->with('success', 'تم حذف الاستبيان');
    }
}