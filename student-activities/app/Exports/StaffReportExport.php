<?php

namespace App\Exports;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fromDate;
    protected $toDate;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function collection()
    {
        $query = Activity::where('supervisor_id', Auth::id());

        if ($this->fromDate) {
            $query->where('date', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $query->where('date', '<=', $this->toDate);
        }

        return $query->with(['activityType', 'registrations'])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'عنوان النشاط',
            'النوع',
            'التاريخ',
            'الوقت',
            'المكان',
            'عدد المسجلين',
            'الحد الأقصى',
            'النقاط',
            'الحالة',
        ];
    }

    public function map($activity): array  // ✅ تم التصحيح
    {
        return [
            $activity->id,
            $activity->title,
            $activity->activityType->name ?? 'عام',
            Carbon::parse($activity->date)->format('Y/m/d'),
            $activity->time,
            $activity->location,
            $activity->registrations->count(),
            $activity->max_participants ?? '∞',
            $activity->points ?? 0,
            $activity->status ?? 'مفتوح',
        ];
    }
}