<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // ✅ تأكد إن هذا السطر موجود

class SurveyStatsController extends Controller
{
    public function index()
    {
        // ... (كود دالة index موجود مسبقاً لا تلمسه) ...
        $questions = SurveyQuestion::all();
        $responses = SurveyResponse::all();
        
        $totalQuestions = $questions->count();
        $totalResponses = $responses->count();
        $totalStudents = $responses->unique('student_id')->count();
        
        if ($totalQuestions > 0 && $totalStudents > 0) {
            $maxPossibleResponses = $totalQuestions * $totalStudents;
            $participationRate = $maxPossibleResponses > 0 ? round(($totalResponses / $maxPossibleResponses) * 100, 2) : 0;
        } else {
            $participationRate = 0;
        }
        
        $agreeCount = $responses->whereIn('answer', ['موافق', 'أوافق بشدة'])->count();
        $neutralCount = $responses->where('answer', 'محايد')->count();
        $disagreeCount = $responses->whereIn('answer', ['لا أوافق', 'لا اوافق'])->count();
        
        return view('admin.survey-stats.index', compact(
            'totalQuestions', 'totalResponses', 'totalStudents', 
            'participationRate', 'agreeCount', 'neutralCount', 'disagreeCount'
        ));
    }

    // ✅ دالة تصدير PDF
    public function exportPDF()
    {
        $responses = SurveyResponse::all();
        $agreeCount = $responses->whereIn('answer', ['موافق', 'أوافق بشدة'])->count();
        $neutralCount = $responses->where('answer', 'محايد')->count();
        $disagreeCount = $responses->whereIn('answer', ['لا أوافق', 'لا اوافق'])->count();
        $total = $responses->count();

        $html = '
        <html dir="rtl" style="font-family: sans-serif;">
        <head><meta charset="utf-8"><title>تقرير الاستبيان</title></head>
        <body style="padding: 20px;">
            <h1 style="text-align: center; color: #2d3748;">تقرير إحصائيات الاستبيان</h1>
            <hr style="border: 1px solid #e2e8f0; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background-color: #4c51bf; color: white;">
                        <th style="padding: 10px; border: 1px solid #ddd;">البيان</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">العدد</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">النسبة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">موافق</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . $agreeCount . '</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . ($total > 0 ? round(($agreeCount/$total)*100) : 0) . '%</td>
                    </tr>
                    <tr style="background-color: #f7fafc;">
                        <td style="padding: 10px; border: 1px solid #ddd;">محايد</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . $neutralCount . '</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . ($total > 0 ? round(($neutralCount/$total)*100) : 0) . '%</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">لا أوافق</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . $disagreeCount . '</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . ($total > 0 ? round(($disagreeCount/$total)*100) : 0) . '%</td>
                    </tr>
                </tbody>
            </table>
            <p style="margin-top: 30px; font-size: 12px; color: #718096;">تاريخ الطباعة: ' . now()->format('Y/m/d H:i') . '</p>
        </body>
        </html>';

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('survey-report-' . now()->format('Y-m-d') . '.pdf');
    }
}