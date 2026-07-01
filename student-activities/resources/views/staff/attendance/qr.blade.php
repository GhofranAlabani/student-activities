<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>QR Code - {{ $activity->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl p-12 max-w-md w-full mx-4 text-center">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-qrcode text-blue-600"></i>
                QR Code للحضور
            </h1>
            <p class="text-gray-600 font-bold">{{ $activity->title }}</p>
            <p class="text-sm text-gray-500 mt-2">
                <i class="fas fa-calendar ml-1"></i> {{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}
                <i class="fas fa-clock mx-2"></i> {{ $activity->time }}
            </p>
        </div>

        <!-- QR Code -->
        <div class="bg-white p-6 rounded-xl mb-6 border-4 border-blue-100 inline-block">
            <img src="{{ $qrCodeUrl }}" alt="QR Code" class="w-72 h-72">
        </div>

        <!-- تعليمات -->
        <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mb-6">
            <p class="text-blue-800 text-sm">
                <i class="fas fa-info-circle ml-1"></i>
                اطلب من الطلاب مسح هذا الكود لتسجيل الحضور
            </p>
            <p class="text-blue-600 text-xs mt-2">
                <i class="fas fa-clock ml-1"></i>
                صالح لمدة ساعتين من الآن
            </p>
        </div>

        <!-- أزرار -->
        <div class="flex gap-3 no-print">
            <a href="{{ route('staff.attendance.index', $activity) }}" 
               class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl hover:bg-gray-300 transition font-bold">
                <i class="fas fa-arrow-right ml-2"></i> العودة
            </a>
            <button onclick="window.print()" 
                    class="flex-1 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-bold">
                <i class="fas fa-print ml-2"></i> طباعة
            </button>
        </div>
    </div>

</body>
</html>