<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{
    /**
     * عرض كل الأسئلة
     */
    public function index()
    {
        $questions = SurveyQuestion::latest()->get();
        return view('admin.survey-questions.index', compact('questions'));
    }

    /**
     * عرض صفحة إضافة سؤال
     */
    public function create()
    {
        return view('admin.survey-questions.create');
    }

    /**
     * حفظ السؤال الجديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
        ]);

        SurveyQuestion::create([
            'question' => $request->question,
        ]);

        return redirect()->route('admin.survey-questions.index')
            ->with('success', 'تم إضافة السؤال بنجاح');
    }

    /**
     * عرض صفحة تعديل السؤال
     */
    public function edit($id)
    {
        $question = SurveyQuestion::findOrFail($id);
        return view('admin.survey-questions.edit', compact('question'));
    }

    /**
     * تحديث السؤال
     */
    public function update(Request $request, $id)
    {
        $question = SurveyQuestion::findOrFail($id);
        
        $request->validate([
            'question' => 'required|string|max:255',
        ]);

        $question->update([
            'question' => $request->question,
        ]);

        return redirect()->route('admin.survey-questions.index')
            ->with('success', 'تم تحديث السؤال بنجاح');
    }

    /**
     * حذف سؤال
     */
    public function destroy($id)
    {
        SurveyQuestion::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف السؤال');
    }

    /**
     * إعادة الأسئلة الافتراضية
     */
    public function resetToDefault()
    {
        SurveyQuestion::truncate();
        
        $questions = [
            'القسم الأول: تقييم المدرب',
            'المدرب كان متمكناً من المادة العلمية',
            'شرح المدرب كان واضحاً وسهل الفهم',
            'المدرب شجع على المشاركة والتفاعل',
            'أجاب المدرب عن الأسئلة والاستفسارات بوضوح',
            'التزم المدرب بوقت النشاط',
            'استخدم المدرب أساليب عرض مناسبة',
            'كان المدرب متعاوناً مع المشاركين',
            'القسم الثاني: تقييم النشاط',
            'النشاط كان مفيداً لي',
            'تم تحقيق أهداف النشاط بنجاح',
            'محتوى النشاط كان واضحاً ومنظماً',
            'المكان كان مناسباً لإقامة النشاط',
            'مدة النشاط كانت مناسبة',
            'التجهيزات المستخدمة كانت مناسبة',
            'تم تنظيم النشاط بشكل جيد',
            'ساهم النشاط في تطوير مهاراتي',
            'القسم الثالث: التقييم العام',
            'أرغب في المشاركة في أنشطة مشابهة مستقبلاً',
            'أنصح زملائي بالمشاركة في هذا النشاط',
            'أشعر بالرضا العام عن هذا النشاط',
        ];

        foreach ($questions as $q) {
            SurveyQuestion::create(['question' => $q]);
        }

        return back()->with('success', 'تم إعادة الأسئلة الافتراضية');
    }
}