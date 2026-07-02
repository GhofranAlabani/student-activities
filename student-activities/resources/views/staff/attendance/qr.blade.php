<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code للحضور - {{ $activity->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .bg-navy-light { background-color: #112240; }
        .text-navy { color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .border-gold { border-color: #d4a017; }
        
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-page { box-shadow: none !important; }
        }
        
        /* تأثير نبض للـ QR */
        @keyframes pulse-border {
            0%, 100% { box-shadow: 0 0 0 0 rgba(212, 160, 23, 0.4); }
            50% { box-shadow: 0 0 0 15px rgba(212, 160, 23, 0); }
        }
        .qr-pulse { animation: pulse-border 2s infinite; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen py-8">

    <div class="container mx-auto px-4 max-w-3xl">
        
        <!-- Header (للطباعة) -->
        <div class="hidden print:block text-center mb-6">
            <h1 class="text-2xl font-black text-navy">شهادة حضور / QR Code</h1>
            <p class="text-gray-600">{{ $activity->title }}</p>
        </div>

        <!-- البطاقة الرئيسية -->
        <div class="print-page bg-white rounded-3xl shadow-2xl overflow-hidden border-t-8 border-gold">
            
            <!-- Header البطاقة -->
            <div class="bg-gradient-to-r from-navy to-navy-light text-white p-8 text-center relative overflow-hidden">
                <!-- زخرفة -->
                <div class="absolute top-0 left-0 w-40 h-40 bg-gold/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-40 h-40 bg-gold/5 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
                
                <div class="relative z-10">
                    <div class="inline-block bg-gold/20 p-4 rounded-full mb-4 border-2 border-gold/30">
                        <i class="fas fa-qrcode text-gold text-4xl"></i>
                    </div>
                    <h1 class="text-3xl font-black mb-2">QR Code للحضور</h1>
                    <p class="text-gold font-bold text-lg">{{ $activity->title }}</p>
                </div>
            </div>

            <!-- معلومات النشاط -->
            <div class="p-8 bg-gray-50 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div class="flex items-center justify-center gap-2 bg-white p-3 rounded-xl shadow-sm">
                        <i class="fas fa-calendar-alt text-gold text-xl"></i>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">التاريخ</p>
                            <p class="font-bold text-navy">{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-2 bg-white p-3 rounded-xl shadow-sm">
                        <i class="fas fa-clock text-gold text-xl"></i>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">الوقت</p>
                            <p class="font-bold text-navy">{{ $activity->time }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-2 bg-white p-3 rounded-xl shadow-sm">
                        <i class="fas fa-map-marker-alt text-gold text-xl"></i>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">المكان</p>
                            <p class="font-bold text-navy text-sm">{{ Str::limit($activity->location, 25) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            <div class="p-12 text-center">
                <div class="inline-block bg-white p-8 rounded-3xl shadow-lg border-4 border-gold qr-pulse mb-6">
                    <img src="{{ $qrCodeUrl }}" alt="QR Code" class="w-72 h-72 mx-auto">
                </div>
                
                <p class="text-navy font-bold text-lg mb-2">
                    <i class="fas fa-mobile-alt text-gold ml-2"></i>
                    امسح الكود بكاميرا الهاتف
                </p>
                <p class="text-gray-500 text-sm">
                    سيتم تسجيل حضورك تلقائياً في النشاط
                </p>
            </div>

            <!-- تعليمات -->
            <div class="mx-8 mb-8 bg-gradient-to-r from-gold/10 to-gold/5 rounded-2xl p-6 border-r-4 border-gold">
                <h3 class="font-black text-navy text-lg mb-3 flex items-center gap-2">
                    <i class="fas fa-list-check text-gold"></i>
                    خطوات تسجيل الحضور
                </h3>
                <div class="space-y-2">
                    <div class="flex items-start gap-3">
                        <span class="bg-gold text-navy w-6 h-6 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">1</span>
                        <p class="text-gray-700 text-sm">افتح كاميرا الهاتف أو تطبيق QR Scanner</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="bg-gold text-navy w-6 h-6 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">2</span>
                        <p class="text-gray-700 text-sm">وجّه الكاميرا نحو الكود أعلاه</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="bg-gold text-navy w-6 h-6 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">3</span>
                        <p class="text-gray-700 text-sm">اضغط على الرابط الذي سيظهر لتأكيد الحضور</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 p-6 text-center border-t border-gray-200">
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-user-shield text-gold ml-1"></i>
                    المشرف: <strong class="text-navy">{{ auth()->user()->name }}</strong>
                    <span class="mx-2">|</span>
                    <i class="fas fa-clock text-gold ml-1"></i>
                    تم الإنشاء: {{ now()->format('Y/m/d H:i') }}
                </p>
            </div>
        </div>

        <!-- أزرار التحكم -->
        <div class="flex gap-3 mt-6 no-print">
            <a href="{{ route('staff.attendance.index', $activity) }}" 
               class="flex-1 bg-white text-navy py-4 rounded-xl hover:bg-gray-50 transition font-bold shadow-lg border border-gray-200 flex items-center justify-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>العودة لسجل الحضور</span>
            </a>
            <button onclick="window.print()" 
                    class="flex-1 bg-gold text-navy py-4 rounded-xl hover:bg-yellow-600 transition font-bold shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-print"></i>
                <span>طباعة / حفظ PDF</span>
            </button>
        </div>

        <!-- رابط بديل -->
        <div class="bg-white rounded-xl shadow p-4 mt-4 no-print">
            <p class="text-gray-600 text-sm mb-2 flex items-center gap-2">
                <i class="fas fa-link text-gold"></i>
                <strong>رابط بديل</strong> (في حال عدم عمل QR Code):
            </p>
            <code class="bg-gray-50 px-3 py-2 rounded-lg text-xs text-navy break-all block border border-gray-200">
                {{ route('attendance.scan') }}?activity_id={{ $activity->id }}
            </code>
        </div>

    </div>

</body>
</html>