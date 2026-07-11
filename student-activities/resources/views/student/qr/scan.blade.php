<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الحضور - QR Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background: linear-gradient(135deg, #0a1929 0%, #112240 100%); }
        .scanner-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        #reader {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .scan-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #d4a017, transparent);
            animation: scan 2s ease-in-out infinite;
        }
        @keyframes scan {
            0%, 100% { top: 0; }
            50% { top: 100%; }
        }
        .success-animation {
            animation: successPulse 0.6s ease;
        }
        @keyframes successPulse {
            0% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <!-- الهيدر -->
        <div class="text-center mb-8">
            <div class="inline-block bg-gold/20 p-4 rounded-full mb-4">
                <i class="fas fa-qrcode text-5xl text-gold"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">تسجيل الحضور</h1>
            <p class="text-gray-300">امسح QR Code لتسجيل حضورك</p>
        </div>

        <!-- منطقة المسح -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-6">
            <!-- حالة المسح -->
            <div id="scanner-status" class="text-center mb-6">
                <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-full">
                    <i class="fas fa-camera animate-pulse"></i>
                    <span class="font-bold">جاري تشغيل الكاميرا...</span>
                </div>
            </div>

            <!-- منطقة الكاميرا -->
            <div class="scanner-container">
                <div id="reader"></div>
                <div class="scan-line" id="scan-line"></div>
            </div>

            <!-- رسالة الانتظار -->
            <div id="waiting-message" class="text-center mt-6">
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-info-circle ml-1"></i>
                    وجّه الكاميرا نحو QR Code
                </p>
            </div>

            <!-- رسالة النجاح (مخفية) -->
            <div id="success-message" class="hidden text-center py-8">
                <div class="success-animation">
                    <div class="inline-block bg-green-100 p-6 rounded-full mb-4">
                        <i class="fas fa-check-circle text-6xl text-green-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-green-700 mb-2">تم تسجيل الحضور بنجاح!</h2>
                    <p class="text-gray-600 mb-4" id="success-details"></p>
                    <div class="bg-gradient-to-r from-gold/20 to-yellow-50 rounded-xl p-4 mb-4">
                        <p class="text-navy font-bold text-lg">
                            <i class="fas fa-coins text-gold ml-2"></i>
                            النقاط المكتسبة: <span id="points-earned" class="text-gold font-bold text-xl">0</span>
                        </p>
                        <p class="text-gray-600 text-sm mt-1">
                            إجمالي نقاطك: <span id="total-points" class="font-bold">0</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- رسالة الخطأ (مخفية) -->
            <div id="error-message" class="hidden text-center py-8">
                <div class="inline-block bg-red-100 p-6 rounded-full mb-4">
                    <i class="fas fa-times-circle text-6xl text-red-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-red-700 mb-2">حدث خطأ!</h2>
                <p class="text-gray-600 mb-4" id="error-details"></p>
                <button onclick="location.reload()" class="bg-navy text-white px-6 py-3 rounded-xl font-bold hover:bg-navy-light transition">
                    <i class="fas fa-redo ml-2"></i>
                    حاول مرة أخرى
                </button>
            </div>
        </div>

        <!-- تعليمات -->
        <div class="bg-white/10 backdrop-blur rounded-2xl p-6 text-center">
            <h3 class="text-white font-bold mb-3">
                <i class="fas fa-lightbulb text-gold ml-2"></i>
                تعليمات الاستخدام
            </h3>
            <ul class="text-gray-300 text-sm space-y-2">
                <li><i class="fas fa-check text-gold ml-2"></i> تأكد من وجودك في قاعة النشاط</li>
                <li><i class="fas fa-check text-gold ml-2"></i> وجّه الكاميرا نحو QR Code المعروض</li>
                <li><i class="fas fa-check text-gold ml-2"></i> انتظر حتى يتم تسجيل الحضور تلقائياً</li>
               