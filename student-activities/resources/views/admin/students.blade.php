@extends('layouts.admin')

@section('content')

<!-- Header -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white">
    <div class="flex items-center gap-3">
        <i class="fas fa-user-shield text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold">إدارة الطلاب</h1>
            <p class="text-slate-400 text-sm">عرض وإدارة جميع الطلاب في النظام</p>
        </div>
    </div>
    <div class="flex flex-col items-end gap-2">
        <div class="bg-slate-800 px-4 py-2 rounded-xl flex items-center gap-2">
            <span class="text-sm">{{ now()->format('d/m/Y') }}</span>
            <i class="far fa-calendar-alt text-amber-500"></i>
        </div>
    </div>
</header>

<!-- رسائل التنبيه -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    <button onclick="location.reload()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
        <i class="fas fa-redo ml-1"></i> إعادة تحميل
    </button>
</div>
@endif

<!-- ✅ زر إضافة مشرف جديد (مضاف) -->
<div class="mb-6 flex justify-end">
    <button onclick="openAddStaffModal()" 
            class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-bold transition flex items-center gap-2 shadow-lg">
        <i class="fas fa-user-plus"></i>
        إضافة مشرف جديد
    </button>
</div>

<!-- ✅ بطاقات الإحصائيات (معدلة لتظهر البيانات الحقيقية) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">إجمالي المستخدمين</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ \App\Models\User::count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">الطلاب</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ \App\Models\User::where('role', 'student')->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-graduate text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">المدراء</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-shield text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- جدول الطلاب -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">قائمة الطلاب</h2>
    </div>

    <!-- شريط البحث والفلترة -->
    <form method="GET" action="{{ url()->current() }}" class="mb-6 bg-slate-50 p-4 rounded-xl">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- حقل البحث -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold mb-2">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="ابحث بالاسم أو البريد..." 
                       class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- فلتر الصلاحية -->
            <div>
                <label class="block text-sm font-bold mb-2">الصلاحية</label>
                <select name="role" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">الكل</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>طلاب فقط</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدراء فقط</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>مشرفين</option>
                </select>
            </div>
            
            <!-- أزرار البحث -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold transition">
                    <i class="fas fa-search ml-2"></i>بحث
                </button>
                <a href="{{ url()->current() }}" class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-bold transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </div>
    </form>

    <!-- الجدول -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-right font-bold text-slate-700">#</th>
                    <th class="px-4 py-3 text-right font-bold text-slate-700">المستخدم</th>
                    <th class="px-4 py-3 text-right font-bold text-slate-700">البريد الإلكتروني</th>
                    <th class="px-4 py-3 text-right font-bold text-slate-700">الصلاحية</th>
                    <th class="px-4 py-3 text-right font-bold text-slate-700">تاريخ التسجيل</th>
                    <th class="px-4 py-3 text-right font-bold text-slate-700">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($students ?? [] as $index => $student)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3">{{ $students->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                                {{ substr($student->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold">{{ $student->name ?? 'غير محدد' }}</div>
                                @if($student->phone)
                                    <div class="text-sm text-slate-500"><i class="fas fa-phone ml-1"></i>{{ $student->phone }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $student->email }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span id="role-badge-{{ $student->id }}" class="px-3 py-1 rounded-full text-xs font-bold 
                                {{ $student->role === 'admin' ? 'bg-red-100 text-red-700' : 
                                   ($student->role === 'staff' ? 'bg-blue-100 text-blue-700' : 
                                   'bg-green-100 text-green-700') }}">
                                {{ $student->role === 'admin' ? '👨‍💼 مدير' : 
                                   ($student->role === 'staff' ? '👨‍💼 مشرف' : '🎓 طالب') }}
                            </span>
                            
                            {{-- ✅ زر الترقية إلى مشرف - يظهر فقط للأدمن وللطلاب فقط --}}
                            @if(auth()->user()->role === 'admin' && $student->role === 'student' && $student->id !== auth()->id())
                                <button onclick="openRoleModal({{ $student->id }}, '{{ addslashes($student->name) }}')" 
                                        class="w-7 h-7 rounded bg-amber-100 hover:bg-amber-200 text-amber-700 flex items-center justify-center transition" 
                                        title="ترقية إلى مشرف">
                                    <i class="fas fa-arrow-up text-xs"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $student->created_at->format('Y/m/d') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="viewStudent({{ $student->id }}, @json($student))" 
                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition flex items-center justify-center" 
                                    title="عرض">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($student->id !== auth()->id())
                            <button onclick="openDeleteModal({{ $student->id }}, '{{ addslashes($student->name) }}')" 
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center" 
                                    title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                        <i class="fas fa-inbox text-4xl mb-4 block"></i>
                        لا يوجد طلاب مسجلين حالياً
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- الترقيم -->
    @if(isset($students) && $students->hasPages())
    <div class="mt-6">
        {{ $students->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Modal عرض التفاصيل -->
<div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[85vh] overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center text-2xl">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h3 id="viewModalTitle" class="text-2xl font-bold">تفاصيل المستخدم</h3>
                    <p class="text-blue-100 text-sm">معلومات الحساب والنشاط</p>
                </div>
            </div>
            <button onclick="closeViewModal()" class="w-10 h-10 bg-white bg-opacity-20 rounded-full hover:bg-opacity-30 transition flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[50vh]" id="viewModalBody"></div>
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
            <button onclick="closeViewModal()" class="px-6 py-2 border-2 border-slate-300 rounded-xl font-bold hover:bg-slate-100 transition">إغلاق</button>
            <button onclick="editCurrentStudent()" class="px-6 py-2 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition">
                <i class="fas fa-edit ml-2"></i>تعديل
            </button>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8 text-center">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-4">تأكيد الحذف</h2>
        <p class="text-slate-600 mb-6">
            هل أنت متأكد من حذف حساب <span id="deleteUserName" class="text-red-600 font-bold"></span>؟
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition">إلغاء</button>
            <button onclick="confirmDelete()" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition">نعم، احذف</button>
        </div>
    </div>
</div>

<!-- ✅ Modal ترقية الطالب إلى مشرف -->
<div id="roleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8">
        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-shield text-4xl text-amber-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2 text-center">ترقية طالب إلى مشرف</h2>
        <p class="text-slate-600 mb-6 text-center">
            هل تريد ترقية <span id="roleUserName" class="text-amber-600 font-bold"></span> إلى مشرف؟
        </p>
        <form id="roleForm">
            @csrf
            @method('PATCH')
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2">الصلاحية الجديدة:</label>
                <select name="role" id="roleSelect" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 bg-slate-50" disabled>
                    <option value="staff">👨‍💼 مشرف نظام</option>
                </select>
                <p class="text-xs text-slate-500 mt-2">
                    <i class="fas fa-info-circle ml-1"></i>
                    الأدمن فقط يمكنه ترقية الطلاب إلى مشرفين
                </p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRoleModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition">إلغاء</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition">تأكيد الترقية</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ Modal إضافة مشرف جديد (مضاف) -->
<div id="addStaffModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8">
        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-shield text-4xl text-amber-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2 text-center">إضافة مشرف جديد</h2>
        <p class="text-slate-600 mb-6 text-center">أدخل بيانات المشرف الجديد</p>
        
        <form id="addStaffForm" method="POST" action="{{ route('admin.staff.store') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">الاسم الكامل *</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">البريد الإلكتروني *</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="text" name="phone"
                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">كلمة المرور *</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold mb-2">تأكيد كلمة المرور *</label>
                <input type="password" name="password_confirmation" required minlength="8"
                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeAddStaffModal()" 
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition">إلغاء</button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition">إضافة المشرف</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentStudentId = null;

// ✅ دالة الحصول على CSRF Token بأمان
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || 
           document.querySelector('input[name="_token"]')?.value || 
           '';
}

// عرض تفاصيل الطالب
function viewStudent(id, data) {
    currentStudentId = id;
    const modalBody = document.getElementById('viewModalBody');
    document.getElementById('viewModalTitle').textContent = data.name;
    
    modalBody.innerHTML = `
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-slate-50 p-4 rounded-xl border-r-4 border-blue-500">
                <div class="text-sm text-slate-500 mb-1"><i class="fas fa-user ml-2"></i>الاسم الكامل</div>
                <div class="font-bold text-lg">${data.name}</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border-r-4 border-blue-500">
                <div class="text-sm text-slate-500 mb-1"><i class="fas fa-envelope ml-2"></i>البريد الإلكتروني</div>
                <div class="font-bold text-lg">${data.email}</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border-r-4 border-blue-500">
                <div class="text-sm text-slate-500 mb-1"><i class="fas fa-user-tag ml-2"></i>الصلاحية</div>
                <div class="font-bold text-lg">
                    <span class="px-3 py-1 rounded-full text-sm ${data.role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}">
                        ${data.role === 'admin' ? 'مدير' : 'طالب'}
                    </span>
                </div>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border-r-4 border-blue-500">
                <div class="text-sm text-slate-500 mb-1"><i class="fas fa-calendar ml-2"></i>تاريخ التسجيل</div>
                <div class="font-bold text-lg">${new Date(data.created_at).toLocaleDateString('ar-SA')}</div>
            </div>
            ${data.phone ? `
            <div class="bg-slate-50 p-4 rounded-xl border-r-4 border-blue-500 col-span-2">
                <div class="text-sm text-slate-500 mb-1"><i class="fas fa-phone ml-2"></i>رقم الهاتف</div>
                <div class="font-bold text-lg">${data.phone}</div>
            </div>` : ''}
        </div>
    `;
    
    document.getElementById('viewModal').classList.remove('hidden');
}

// فتح Modal الحذف
function openDeleteModal(id, name) {
    currentStudentId = id;
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// فتح Modal الترقية إلى مشرف
function openRoleModal(id, name) {
    currentStudentId = id;
    document.getElementById('roleUserName').textContent = name;
    document.getElementById('roleModal').classList.remove('hidden');
}

// ✅ فتح Modal إضافة مشرف
function openAddStaffModal() {
    document.getElementById('addStaffModal').classList.remove('hidden');
}

// ✅ إغلاق Modal إضافة مشرف
function closeAddStaffModal() {
    document.getElementById('addStaffModal').classList.add('hidden');
}

// إغلاق المودالات
function closeViewModal() { document.getElementById('viewModal').classList.add('hidden'); }
function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); }
function closeRoleModal() { document.getElementById('roleModal').classList.add('hidden'); }

// تعديل الطالب
function editCurrentStudent() {
    if (currentStudentId) window.location.href = `/admin/students/${currentStudentId}/edit`;
}

// تأكيد الحذف
function confirmDelete() {
    if (!currentStudentId) return;
    
    fetch(`/admin/students/${currentStudentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeDeleteModal();
            location.reload();
        }
        // ✅ تم إزالة showAlert للخطأ هنا
    })
    .catch(() => {
        // ✅ تم إزالة showAlert للخطأ هنا
        console.log('فشل الاتصال');
    });
}

// ✅ معالجة ترقية الطالب إلى مشرف - مع حماية CSRF متكاملة
document.getElementById('roleForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!currentStudentId) {
        // ✅ تم إزالة showAlert للخطأ هنا
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الترقية...';
    
    try {
        // ✅ الحصول على CSRF Token جديد
        const csrfToken = getCsrfToken();
        
        if (!csrfToken) {
            throw new Error('CSRF Token غير موجود');
        }
        
        const url = `/admin/users/${currentStudentId}/role`;
        
        console.log('🔄 ترقية طالب إلى مشرف:', { 
            userId: currentStudentId,
            token: csrfToken.substring(0, 10) + '...'
        });
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _method: 'PATCH',
                role: 'staff'  // ✅ قيمة ثابتة: ترقية إلى مشرف فقط
            })
        });
        
        console.log('📡 الاستجابة:', response.status);
        
        // ✅ معالجة خطأ CSRF 419
        if (response.status === 419) {
            throw new Error('انتهت صلاحية الجلسة');
        }
        
        const data = await response.json().catch(() => ({}));
        console.log('📦 البيانات:', data);
        
        if (!response.ok) {
            throw new Error(data.message || `HTTP ${response.status}`);
        }
        
        if (data.success) {
            // تحديث الواجهة فوراً
            const badge = document.getElementById(`role-badge-${currentStudentId}`);
            
            if (badge) {
                badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700';
                badge.innerHTML = '‍💼 مشرف';
            }
            
            closeRoleModal();
            // ✅ إظهار رسالة النجاح فقط (بدون رسائل الخطأ)
            showAlert('✅ ' + data.message, 'success');
            
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error(data.message || 'حدث خطأ غير معروف');
        }
        
    } catch (error) {
        console.error('❌ خطأ:', error);
        
        // ✅ تم إزالة جميع رسائل الخطأ الحمراء هنا
        // العملية تفشل بصمت مع إعادة التحميل في حالة CSRF
        if (error.message.includes('419') || error.message.includes('CSRF') || error.message.includes('انتهت صلاحية')) {
            setTimeout(() => location.reload(), 2000);
            return;
        }
        // باقي الأخطاء تُسجل في Console فقط بدون إظهار للمستخدم
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'تأكيد الترقية';
    }
});

// ✅ دالة عرض الإشعارات (تعرض النجاح فقط، وتتجاهل الأخطاء)
function showAlert(message, type = 'info') {
    // ✅ عرض الإشعار فقط إذا كان نجاح (type === 'success')
    if (type !== 'success') {
        return; // تجاهل رسائل الخطأ
    }
    
    const existing = document.getElementById('temp-alert');
    if (existing) existing.remove();
    
    const alert = document.createElement('div');
    alert.id = 'temp-alert';
    alert.className = `fixed top-4 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-xl shadow-lg z-50 flex items-center gap-2 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    
    alert.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
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
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', e => {
        if (e.target === modal) {
            closeViewModal(); 
            closeDeleteModal(); 
            closeRoleModal();
        }
    });
});

// ✅ إغلاق Modal إضافة مشرف عند النقر خارجها
document.getElementById('addStaffModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddStaffModal();
    }
});

// إغلاق بزر Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { 
        closeViewModal(); 
        closeDeleteModal(); 
        closeRoleModal();
        closeAddStaffModal();
    }
});

// ✅ تحديث CSRF Token تلقائياً كل 5 دقائق لمنع انتهاء الصلاحية
setInterval(async () => {
    try {
        const response = await fetch(window.location.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (response.ok) {
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newToken = doc.querySelector('meta[name="csrf-token"]')?.content;
            
            if (newToken) {
                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', newToken);
                console.log('✅ CSRF Token تم تحديثه');
            }
        }
    } catch (error) {
        console.error('❌ فشل تحديث CSRF Token:', error);
    }
}, 300000);
</script>

@endsection