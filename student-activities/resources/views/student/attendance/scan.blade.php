<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مسح QR Code - تسجيل الحضور</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-qrcode text-blue-600"></i>
                تسجيل الحضور
            </h1>
            <p class="text-gray-600 mb-6">امسح QR Code الذي يعرضه المشرف</p>

            <!-- منطقة الكاميرا -->
            <div id="reader" class="mb-6 rounded-xl overflow-hidden border-4 border-blue-100"></div>

            <!-- زر المسح -->
            <button onclick="startScan()" id="startBtn"
                    class="bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700 transition font-bold mb-4">
                <i class="fas fa-camera ml-2"></i> تشغيل الكاميرا
            </button>

            <!-- إدخال يدوي -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-600 mb-3 font-bold">أو أدخل الكود يدوياً:</p>
                <form action="{{ route('attendance.check-in-qr') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="qr_data" placeholder="الصق بيانات QR هنا..."
                           class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                    <button type="submit" 
                            class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition font-bold">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
            </div>

            <!-- رسائل -->
            <div id="message" class="mt-6 hidden"></div>
        </div>

        <!-- رابط العودة -->
        <div class="text-center mt-6">
            <a href="{{ route('attendance.index') }}" class="text-blue-600 hover:underline font-bold">
                <i class="fas fa-arrow-right ml-1"></i> العودة لسجل الحضور
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
            
            // إرسال البيانات للـ server
            fetch('{{ route("attendance.check-in-qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ qr_data: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', '✅ ' + data.message + ' (+ ' + data.points + ' نقطة)');
                } else {
                    showMessage('error', '❌ ' + data.message);
                }
            })
            .catch(error => {
                showMessage('error', 'حدث خطأ: ' + error);
            });
        }

        function onScanFailure(error) {
            // تجاهل الأخطاء أثناء المسح المستمر
        }

        function showMessage(type, text) {
            const msgDiv = document.getElementById('message');
            msgDiv.classList.remove('hidden');
            
            if (type === 'success') {
                msgDiv.className = 'mt-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl';
            } else {
                msgDiv.className = 'mt-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl';
            }
            
            msgDiv.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' ml-2"></i>' + text;
        }
    </script>

</body>
</html>