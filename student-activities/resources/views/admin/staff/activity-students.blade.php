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
                                    <div class="flex gap-2">
                                        {{-- ✅ زر عرض الملف --}}
                                        <a href="{{ route('admin.students.show', $student->id) }}" 
                                           class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition flex items-center justify-center" 
                                           title="عرض الملف">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        {{-- ✅ زر تعديل التسجيل (جديد) --}}
                                        <button onclick="openEditModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->status ?? 'مسجل' }}')" 
                                                class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition flex items-center justify-center" 
                                                title="تعديل الحالة">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        {{-- ✅ زر إلغاء التسجيل (حذف) --}}
                                        <button onclick="openDeleteModal({{ $student->id }}, '{{ addslashes($student->name) }}')" 
                                                class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center" 
                                                title="إلغاء التسجيل">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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

<!-- ✅ Modal تعديل حالة التسجيل -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8">
        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-edit text-4xl text-amber-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-navy mb-2 text-center">تعديل حالة التسجيل</h2>
        <p class="text-gray-600 mb-6 text-center">
            الطالب: <span id="editStudentName" class="font-bold text-navy"></span>
        </p>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-navy">حالة التسجيل:</label>
                <select name="status" id="statusSelect" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="مسجل">✅ مسجل</option>
                    <option value="مؤكد">✅ مؤكد</option>
                    <option value="حاضر">✅ حاضر</option>
                    <option value="غائب">❌ غائب</option>
                    <option value="ملغي">❌ ملغي</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEditModal()" 
                        class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">إلغاء</button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ Modal تأكيد إلغاء التسجيل -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8 text-center">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-navy mb-4">تأكيد إلغاء التسجيل</h2>
        <p class="text-gray-600 mb-6">
            هل أنت متأكد من إلغاء تسجيل <span id="deleteStudentName" class="text-red-600 font-bold"></span> من هذا النشاط؟
        </p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">إلغاء</button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition">نعم، إلغاء التسجيل</button>
            </div>
        </form>
    </div>
</div>

@endsection

<script>
let currentStudentId = null;
let currentActivityId = {{ $activity->id }};

// ✅ فتح Modal تعديل الحالة
function openEditModal(id, name, currentStatus) {
    currentStudentId = id;
    document.getElementById('editStudentName').textContent = name;
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('editForm').action = `/staff/activities/${currentActivityId}/registrations/${id}`;
    document.getElementById('editModal').classList.remove('hidden');
}

// ✅ إغلاق Modal التعديل
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// ✅ فتح Modal الحذف
function openDeleteModal(id, name) {
    currentStudentId = id;
    document.getElementById('deleteStudentName').textContent = name;
    document.getElementById('deleteForm').action = `/staff/activities/${currentActivityId}/registrations/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// ✅ إغلاق Modal الحذف
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// ✅ معالجة تعديل الحالة
document.getElementById('editForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري...';
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(this)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('✅ ' + data.message, 'success');
            closeEditModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('❌ ' + (data.message || 'حدث خطأ'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('❌ فشل الاتصال', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// ✅ معالجة إلغاء التسجيل (حذف)
document.getElementById('deleteForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري...';
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(this)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('✅ ' + data.message, 'success');
            closeDeleteModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('❌ ' + (data.message || 'حدث خطأ'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('❌ فشل الاتصال', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// ✅ دالة عرض الإشعارات
function showAlert(message, type = 'info') {
    const existing = document.getElementById('temp-alert');
    if (existing) existing.remove();
    
    const alert = document.createElement('div');
    alert.id = 'temp-alert';
    alert.className = `fixed top-4 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-xl shadow-lg z-50 flex items-center gap-2 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    
    alert.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.style.transition = 'opacity 0.3s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    }, 3000);
}

// إغلاق المودالات عند النقر خارجها
document.querySelectorAll('#editModal, #deleteModal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
            closeDeleteModal();
        }
    });
});

// إغلاق المودالات بزر Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
    }
});
</script>