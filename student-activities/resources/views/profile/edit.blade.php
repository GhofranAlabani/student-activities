@extends('layouts.app')

@section('content')
<div class="py-12" dir="rtl">
    <!-- Header مع زر الرجوع -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 flex items-center justify-between border-b">
                <h2 class="text-2xl font-bold text-gray-800">
                    الملف الشخصي
                </h2>
                
                <!-- زر الرجوع -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition font-semibold shadow-md">
                    <i class="fas fa-arrow-right"></i>
                    رجوع للوحة التحكم
                </a>
            </div>
        </div>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- قسم المعلومات الشخصية -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- قسم تحديث كلمة المرور -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @include('profile.partials.update-password-form')
        </div>

        <!-- قسم حذف الحساب -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</div>
@endsection