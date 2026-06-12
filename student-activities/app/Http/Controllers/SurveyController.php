<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    // ========================
    // 🎓 صفحات الطالب
    // ========================
    
    public function show($activityId)
    {
        $activity = \App\Models\Activity::findOrFail($activityId);
        $questions = SurveyQuestion::where('is_active', true)->orderBy('order')->get();
        $hasResponded = SurveyResponse::where('user_id', auth()->id())->where('activity_id', $activityId)->exists();

        return view('student.survey', compact('activity', 'questions', 'hasResponded'));
    }

    public function store(Request $request, $activityId)
    {
        $questions = SurveyQuestion::where('is_active', true)->get();
        
        $rules = [];
        foreach ($questions as $question) {
            if ($question->is_required) {
                $rules["question_{$question->id}"] = 'required';
            }
        }

        $request->validate($rules, ['required' => 'هذا الحقل مطلوب']);

        DB::beginTransaction();
        try {
            foreach ($questions as $question) {
                $answer = $request->input("question_{$question->id}");
                if (is_array($answer)) $answer = implode(', ', $answer);

                SurveyResponse::updateOrCreate(
                    ['user_id' => auth()->id(), 'activity_id' => $activityId, 'question_id' => $question->id],
                    ['answer' => $answer]
                );
            }
            DB::commit();
            return redirect()->route('student.my-activities')->with('success', '✅ تم إرسال استبيانك بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحفظ');
        }
    }

    // ========================
    // 👨‍💼 صفحات الأدمن
    // ========================

    public function adminIndex()
    {
        $questions = SurveyQuestion::orderBy('order')->withCount('responses')->get();
        return view('admin.survey.index', compact('questions'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'type' => 'required|in:text,radio,checkbox',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_required'] = $request->has('is_required');

        if ($request->type !== 'text' && $request->filled('options')) {
            $options = array_filter(array_map('trim', explode("\n", $request->options)));
            $data['options'] = json_encode(array_values($options));
        }

        SurveyQuestion::create($data);
        return back()->with('success', 'تم إضافة السؤال');
    }

    public function adminDestroy($id)
    {
        SurveyQuestion::destroy($id);
        return back()->with('success', 'تم الحذف');
    }
}