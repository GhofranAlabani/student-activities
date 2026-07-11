@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- الهيدر -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-navy mb-2">
                <i class="fas fa-users ml-2"></i>
                الطلاب المسجلين
            </h1>
            <p class="text-gray-600 text-lg">{{ $activity->title }}</p>
        </div>
        <a href="{{ route('staff.dashboard') }}" 
           class="bg-gray-200 text-navy px-6 py-3 rounded-xl hover:bg-gray-300 transition font-bold">
            <i class="fas fa-arrow-right ml-2"></i> رجوع
        </a>
    </div>

    <!-- معلومات النشاط -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-gray-500 text-sm mb-1">التاريخ</p>
                <p class="font-bold text-navy">{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">الوقت</p>
                <p class="font-bold text-navy">{{ $activity->time }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">المسجلين</p>
                <p class="font-bold text-navy">{{ $students->count() }} / {{ $activity->max_participants }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">الحالة</p>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                    {{ $activity->status }}
                </span>
            </div>
        </div>
    </div>

    <!-- جدول الطلاب -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-navy">قائمة الطلاب المسجلين</h2>
        </div>
        
        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right font-bold text-navy">#</th>
                            <th class="px-6 py-3 text-right font-bold text-navy">الاسم</th>
                            <th class="px-6 py-3 text-right font-bold text-navy">البريد الإلكتروني</th>
                            <th class="px-6 py-3 text-right font-bold text-navy">تاريخ التسجيل</th>
                            <th class="px-6 py-3 text-right font-bold text-navy">الحالة</th>
                            <th class="px-6 py-3 text-right font-bold text-navy">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-navy">{{ $student->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $student->email }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($student->registered_at)->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                        {{ $student->status ?? 'مسجل' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.students.show', $student->id) }}" 
                                       class="text-blue-600 hover:text-blue-800" 
                                       title="عرض الملف">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-users-slash text-6xl mb-4"></i>
                <p class="text-lg">لا يوجد طلاب مسجلين في هذا النشاط</p>
            </div>
        @endif
    </div>
</div>
@endsection