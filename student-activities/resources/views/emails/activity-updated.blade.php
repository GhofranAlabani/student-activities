<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تم تحديث نشاط</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    
    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        
        <!-- الهيدر -->
        <div style="background-color: #0a1929; padding: 30px; text-align: center;">
            <h1 style="color: #d4a017; margin: 0; font-size: 24px;">📢 إشعار تحديث نشاط</h1>
        </div>

        <!-- المحتوى -->
        <div style="padding: 40px 30px;">
            <h2 style="color: #0a1929; margin-top: 0;">مرحباً {{ $user->name }} 👋</h2>
            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                نود إعلامك بأنه تم تحديث تفاصيل النشاط الذي سجلت فيه:
            </p>
            
            <!-- بطاقة النشاط -->
            <div style="background: linear-gradient(135deg, #f5f0e8 0%, #ffffff 100%); border: 2px solid #d4a017; border-radius: 15px; padding: 25px; margin: 30px 0;">
                <h3 style="color: #0a1929; margin: 0 0 20px 0; font-size: 22px; text-align: center;">
                    {{ $activity->title }}
                </h3>
                
                <div style="background-color: #ffffff; border-radius: 10px; padding: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                        <span style="color: #6b7280; font-size: 14px;">📅 التاريخ:</span>
                        <strong style="color: #0a1929;">{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                        <span style="color: #6b7280; font-size: 14px;"> الوقت:</span>
                        <strong style="color: #0a1929;">{{ $activity->time }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                        <span style="color: #6b7280; font-size: 14px;">📍 المكان:</span>
                        <strong style="color: #0a1929;">{{ $activity->location }}</strong>
                    </div>
                </div>
            </div>

            <!-- التغييرات -->
            @if(!empty($changes))
                <div style="background-color: #fef3c7; border-right: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <h4 style="color: #92400e; margin: 0 0 10px 0; font-size: 16px;">🔄 التغييرات التي تمت:</h4>
                    <ul style="color: #78350f; margin: 0; padding-right: 20px; font-size: 14px;">
                        @foreach($changes as $field => $value)
                            <li style="margin-bottom: 5px;">
                                <strong>{{ $field }}:</strong> {{ $value }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- زر -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/student/my-activities') }}" 
                   style="display: inline-block; background-color: #d4a017; color: #0a1929; padding: 15px 40px; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 16px;">
                    عرض أنشطتي
                </a>
            </div>

            <p style="color: #6b7280; font-size: 14px; text-align: center; margin-top: 30px;">
                 إذا كان لديك أي استفسار، يرجى التواصل مع المشرف
            </p>
        </div>

        <!-- الفوتر -->
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; color: #888; font-size: 12px; border-top: 1px solid #eee;">
            <p style="margin: 0;">© 2026 منصة الأنشطة الطلابية. جميع الحقوق محفوظة.</p>
        </div>

    </div>

</body>
</html>