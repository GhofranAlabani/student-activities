<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Student Activities') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

    <!-- Logo & Title -->
    <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center gap-3">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-graduation-cap text-4xl text-indigo-600"></i>
            </div>
        </a>
        <h1 class="text-3xl font-bold text-white mt-4">نظام الأنشطة الطلابية</h1>
        <p class="text-white/80 mt-2">سجل دخولك للمتابعة</p>
    </div>

    <!-- Main Content -->
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl overflow-hidden sm:rounded-2xl">
        {{ $slot }}
    </div>

    <!-- Back to Home -->
    <div class="mt-6 text-center">
        <a href="/" class="text-white hover:text-white/80 font-semibold transition">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للصفحة الرئيسية
        </a>
    </div>

</body>
</html>