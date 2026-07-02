<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسح QR Code - تسجيل الحضور</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <div class="inline-block bg-gold/20 p-4 rounded-full mb-4">
                <i class="fas fa-qrcode text-gold text-4xl"></i>
            </div>
            
            <h1 class="text-3xl font-black text-navy mb-2">تسجيل الحضور</h1>
            <p class="text-gray-600 mb-6">امسح QR Code الذي يعرضه المشرف</p>

            <!-- منطقة الكاميرا -->
            <div id="reader" class="mb-6 rounded-xl overflow-hidden border-4 border-gold/30"></div>

            <!-- زر المسح -->
            <button onclick="startScan()" id="startBtn"
                    class="bg-gold text-navy px-8 py-3 rounded-xl hover:bg-yellow-600 transition font-bold mb-4">
                <i class="fas fa-camera ml-2"></i> تشغيل الكاميرا
            </button>

            <!-- إدخال يدوي -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-600 mb-3 font-bold">
                    <i class="fas fa-keyboard text-gold ml-1"></i>
                    أو أدخل رابط النشاط يدوياً:
                </p>
                
                <form id="manualForm" class="flex gap-2">
                    @csrf
                    <input type="text" id="qrInput" 
                           placeholder="الصق الرابط أو بيانات QR هنا..."
                           class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold text-sm">
                    <button type="submit" 
                            class="bg-gold text-navy px-6 py-3 rounded-xl hover:bg-yellow-600 transition font-bold">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
                
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle ml-1"></i>
                    مثال: http://127.0.0.1:8000/attendance/scan?activity_id=6
                </p>
            </div>

            <!-- رسائل -->
            <div id="message" class="mt-6 hidden"></div>
        </div>

        <!-- رابط العودة -->
        <div class="text-center mt-6">
            <a href="{{ url('/student/dashboard') }}" class="text-gold hover:text-yellow-700 font-bold">
                <i class="fas fa-arrow-right ml-1"></i> العودة للوحة التحكم
            </a>
        </div>
    </div>

    <!-- مكتبة قراءة QR Code -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode;

        function startScan() {
            html5QrCode = new Html5Qrcode("reader");
            
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                showMessage('error', 'فشل تشغيل الكاميرا: ' + err);
            });

            document.getElementById('startBtn').classList.add('hidden');
        }

        function onScanSuccess(decodedText) {
            html5QrCode.stop();
            processQRData(decodedText);
        }

        function onScanFailure(error) {
            // تجاهل الأخطاء أثناء المسح المستمر
        }

        // معالجة بيانات QR من الكاميرا
        function processQRData(decodedText) {
            let activityId = null;
            
            // محاولة 1: إذا كان الرابط يحتوي على activity_id
            const urlMatch = decodedText.match(/activity_id=(\d+)/);
            if (urlMatch) {
                activityId = urlMatch[1];
            }
            // محاولة 2: إذا كان JSON
            else {
                try {
                    const data = JSON.parse(decodedText);
                    activityId = data.activity_id;
                } catch (e) {
                    // محاولة 3: إذا كان رقم عادي
                    if (/^\d+$/.test(decodedText)) {
                        activityId = decodedText;
                    }
                }
            }

            if (!activityId) {
                showMessage('error', 'QR Code غير صالح! تأكد من مسح الكود الصحيح.');
                document.getElementById('startBtn').classList.remove('hidden');
                return;
            }

            // إرسال الطلب للـ server
            submitCheckIn(activityId);
        }

        // إرسال تسجيل الحضور
        function submitCheckIn(activityId) {
            showMessage('info', 'جاري تسجيل الحضور...');

            fetch('{{ route("attendance.check-in-qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ activity_id: activityId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', '✅ ' + data.message + ' (+' + (data.points || 0) + ' نقطة)');
                } else {
                    showMessage('error', '❌ ' + data.message);
                    document.getElementById('startBtn').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('error', 'حدث خطأ في الاتصال بالخادم');
                document.getElementById('startBtn').classList.remove('hidden');
            });
        }

        // معالجة الإدخال اليدوي
        document.getElementById('manualForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('qrInput').value.trim();
            
            if (!input) {
                showMessage('error', 'الرجاء إدخال بيانات QR أو الرابط');
                return;
            }

            processQRData(input);
        });

        function showMessage(type, text) {
            const msgDiv = document.getElementById('message');
            msgDiv.classList.remove('hidden');
            
            const colors = {
                'success': 'bg-green-50 border-green-200 text-green-700',
                'error': 'bg-red-50 border-red-200 text-red-700',
                'info': 'bg-blue-50 border-blue-200 text-blue-700'
            };
            
            const icons = {
                'success': 'check-circle',
                'error': 'exclamation-circle',
                'info': 'spinner fa-spin'
            };
            
            msgDiv.className = 'mt-6 border px-5 py-4 rounded-xl ' + colors[type];
            msgDiv.innerHTML = '<i class="fas fa-' + icons[type] + ' ml-2"></i>' + text;
            
            // التحقق من وجود activity_id في الـ URL
const urlParams = new URLSearchParams(window.location.search);
const activityId = urlParams.get('activity_id');

if (activityId) {
    // تسجيل الحضور تلقائياً
    setTimeout(() => {
        submitCheckIn(activityId);
    }, 1000); // تأخير ثانية واحدة
}
        }
    </script>
   


</body>
</html>