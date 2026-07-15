@extends('layouts.admin')

@section('content')

<!-- Header (Top Bar) -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white relative overflow-hidden">
    
    <!-- اليمين: عنوان القسم -->
    <div class="flex items-center gap-3 z-10">
        <i class="fas fa-user-tie text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">إدارة المشرفين</h1>
            <p class="text-slate-400 text-sm">إدارة جميع المشرفين والمديرين في النظام</p>
        </div>
    </div>

    <!-- اليسار: التاريخ + زر الوضع الليلي -->
    <div class="flex flex-col items-end gap-2 z-10">
        
        <!-- التاريخ -->
        <div class="bg-slate-800 px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-2">
            <span class="font-semibold text-sm">{{ now()->format('d/m/Y') }}</span>
            <i class="far fa-calendar-alt text-amber-500"></i>
        </div>

        <!-- زر الوضع الليلي -->
        <button onclick="toggleDarkMode()" 
                class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-500 transition-all shadow-md"
                title="تبديل الوضع الليلي">
            <i class="fas fa-moon text-xs" id="darkModeIcon"></i>
        </button>

    </div>
</header>

<!-- رسائل النجاح -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<!-- بطاقات الإحصائيات -->
<div class="stats-cards">
    <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
        <div class="stat-icon purple">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3 class="dark:text-slate-400">إجمالي المشرفين</h3>
            <p class="stat-number dark:text-white">{{ $totalStaff ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
        <div class="stat-icon green">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-info">
            <h3 class="dark:text-slate-400">نشطون</h3>
            <p class="stat-number dark:text-white">{{ $activeStaff ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
        <div class="stat-icon blue">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <h3 class="dark:text-slate-400">مديرو النظام</h3>
            <p class="stat-number dark:text-white">{{ $totalAdmins ?? 0 }}</p>
        </div>
    </div>
</div>

<!-- حاوية الجدول -->
<div class="table-container dark:bg-slate-800 dark:border-slate-700">
    <!-- شريط البحث والفلترة -->
    <div class="table-header">
        <h2 class="table-title dark:text-white">قائمة المشرفين</h2>
    </div>

    <!-- الفلاتر المتقدمة -->
    <div class="advanced-filters dark:bg-slate-700 dark:border-slate-600">
        <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <div class="filters-grid">
                <!-- حقل البحث -->
                <div class="filter-group">
                    <label class="dark:text-slate-300"><i class="fas fa-search"></i> البحث</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث بالاسم أو البريد..." class="dark:bg-slate-600 dark:border-slate-500 dark:text-white">
                </div>

                <!-- زر البحث -->
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </div>

                <!-- زر إعادة تعيين -->
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="{{ url()->current() }}" class="btn-reset">
                        <i class="fas fa-redo"></i> إعادة تعيين
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- الجدول -->
    <div class="table-responsive">
        <table class="students-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المشرف</th>
                    <th>البريد الإلكتروني</th>
                    <th>تاريخ التعيين</th>
                    <th>الصلاحية (الحالة)</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff ?? [] as $index => $member)
                <tr class="dark:hover:bg-slate-700/50">
                    <td class="dark:text-slate-300">{{ $index + 1 }}</td>
                    <td>
                        <div class="user-info">
                            <div class="avatar" style="background: #dc2626">
                                {{ substr($member->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <div class="user-name dark:text-white">{{ $member->name ?? 'غير محدد' }}</div>
                                @if($member->phone ?? false)
                                    <div class="user-phone dark:text-slate-400"><i class="fas fa-phone"></i> {{ $member->phone }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="dark:text-slate-400">{{ $member->email ?? 'غير متوفر' }}</td>
                    <td class="dark:text-slate-400">{{ $member->created_at ? $member->created_at->format('Y/m/d') : 'غير محدد' }}</td>
                    
                    <!-- ✅ عمود الصلاحية (قابل للتعديل فوراً) -->
                    <td>
                        @if($member->id == auth()->id())
                            <!-- لا يمكن تغيير صلاحية الشخص لنفسه من هنا -->
                            <span class="role-badge admin">👨‍💼 مدير (أنت)</span>
                        @else
                            <form action="{{ route('admin.staff.updateRole', $member->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="role" onchange="this.form.submit()" class="p-2 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-amber-500 outline-none bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-white" style="direction: rtl; min-width: 120px;">
                                    <option value="admin" {{ $member->role == 'admin' ? 'selected' : '' }}>👨‍💼 مدير</option>
                                    <option value="staff" {{ $member->role == 'staff' ? 'selected' : '' }}>🎓 مشرف</option>
                                    <option value="student" {{ $member->role == 'student' ? 'selected' : '' }}>🎓 طالب</option>
                                </select>
                            </form>
                        @endif
                    </td>

                    <!-- ✅ عمود الإجراءات (حذف وعرض) -->
                    <td>
                        <div class="actions-buttons">
                            <button onclick="viewStaff({{ $member->id }}, @json($member))" class="action-btn view" title="عرض">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            @if($member->id !== auth()->id())
                            <button onclick="openDeleteModal({{ $member->id }}, '{{ addslashes($member->name) }}')" class="action-btn delete" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="no-data dark:text-slate-400">
                        <i class="fas fa-inbox"></i>
                        <p>لا يوجد مشرفين مسجلين حالياً</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ Modal عرض تفاصيل المشرف (مُحسّن) -->
<div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8">
        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user text-4xl text-blue-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2 text-center" id="viewModalTitle">تفاصيل المشرف</h2>
        <div id="viewModalBody" class="text-center text-slate-600 space-y-2"></div>
        <div class="mt-6 text-center">
            <button onclick="closeViewModal()" class="px-6 py-2 bg-slate-500 text-white rounded-xl font-bold hover:bg-slate-600 transition">إغلاق</button>
        </div>
    </div>
</div>

<!-- ✅ Modal تأكيد الحذف -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8 text-center">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-4">تأكيد الحذف</h2>
        <p class="text-slate-600 mb-6">
            هل أنت متأكد من حذف <span id="deleteUserName" class="text-red-600 font-bold"></span> نهائياً؟
        </p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition">إلغاء</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition">نعم، احذف</button>
            </div>
        </form>
    </div>
</div>

@endsection

<style>
    /* نفس الـ CSS اللي في صفحة الطلاب */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
        font-size: 24px;
        color: white;
    }

    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1e40af); }

    .stat-info h3 {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 900;
        color: #1e3a8a;
        margin: 0;
        line-height: 1;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .table-header {
        margin-bottom: 20px;
    }

    .table-title {
        color: #1e3a8a;
        font-size: 20px;
        margin: 0;
    }

    .advanced-filters {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #4f46e5;
    }

    .btn-search,
    .btn-reset {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-search {
        background: #4f46e5;
        color: white;
    }

    .btn-search:hover { background: #4338ca; }

    .btn-reset {
        background: #6b7280;
        color: white;
    }

    .btn-reset:hover { background: #4b5563; }

    .table-responsive { overflow-x: auto; }

    .students-table {
        width: 100%;
        border-collapse: collapse;
    }

    .students-table th,
    .students-table td {
        padding: 14px 12px;
        text-align: right;
        border-bottom: 1px solid #e5e7eb;
    }

    .students-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .students-table tbody tr:hover { background: #f9fafb; }

    .user-info { display: flex; align-items: center; gap: 10px; }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        flex-shrink: 0;
    }

    .user-name { font-weight: 600; color: #111827; }
    .user-phone { font-size: 12px; color: #6b7280; display: flex; align-items: center; gap: 4px; }

    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .role-badge.admin { background: #fee2e2; color: #991b1b; }

    .actions-buttons { display: flex; gap: 8px; }

    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .action-btn.view { background: #dbeafe; color: #2563eb; }
    .action-btn.view:hover { background: #2563eb; color: white; }

    .action-btn.delete { background: #fee2e2; color: #dc2626; }
    .action-btn.delete:hover { background: #dc2626; color: white; }

    .no-data { text-align: center; padding: 40px 20px; color: #9ca3af; }
    .no-data i { font-size: 48px; margin-bottom: 10px; display: block; }

    /* Dark Mode Support */
    html.dark .students-table th {
        background-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    
    html.dark .students-table tbody tr {
        border-bottom-color: #475569;
    }
    
    /* Modal Styles */
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; animation: fadeIn 0.3s ease; }
    .modal.show { display: flex; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<script>
// دالة تبديل الوضع الليلي
function toggleDarkMode() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        icon.className = 'fas fa-moon text-xs';
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        icon.className = 'fas fa-sun text-xs';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        icon.className = 'fas fa-sun text-xs';
    }
});

// ✅ دالة عرض تفاصيل المشرف (مُحسّنة)
function viewStaff(id, data) {
    document.getElementById('viewModalTitle').textContent = data.name;
    document.getElementById('viewModalBody').innerHTML = `
        <div class="space-y-3 text-right">
            <div class="bg-slate-50 p-3 rounded-lg">
                <p class="text-sm text-slate-500 mb-1"><i class="fas fa-user ml-2"></i>الاسم الكامل</p>
                <p class="font-bold text-slate-800">${data.name}</p>
            </div>
            
            <div class="bg-slate-50 p-3 rounded-lg">
                <p class="text-sm text-slate-500 mb-1"><i class="fas fa-envelope ml-2"></i>البريد الإلكتروني</p>
                <p class="font-bold text-slate-800 break-all">${data.email}</p>
            </div>
            
            <div class="bg-slate-50 p-3 rounded-lg">
                <p class="text-sm text-slate-500 mb-1"><i class="fas fa-phone ml-2"></i>رقم الهاتف</p>
                <p class="font-bold text-slate-800">${data.phone || 'غير متوفر'}</p>
            </div>
            
            <div class="bg-slate-50 p-3 rounded-lg">
                <p class="text-sm text-slate-500 mb-1"><i class="fas fa-user-tag ml-2"></i>الصلاحية</p>
                <p class="font-bold text-slate-800">
                    ${data.role === 'admin' ? '👨‍💼 مدير نظام' : 
                      data.role === 'staff' ? '🎓 مشرف' : '🎓 طالب'}
                </p>
            </div>
            
            <div class="bg-slate-50 p-3 rounded-lg">
                <p class="text-sm text-slate-500 mb-1"><i class="fas fa-calendar ml-2"></i>تاريخ التعيين</p>
                <p class="font-bold text-slate-800">${new Date(data.created_at).toLocaleDateString('ar-SA')}</p>
            </div>
        </div>
    `;
    document.getElementById('viewModal').classList.remove('hidden');
}

// ✅ إغلاق Modal العرض
function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

// ✅ فتح Modal الحذف
function openDeleteModal(id, name) {
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteForm').action = '/admin/staff/' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// ✅ إغلاق Modal الحذف
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// ✅ معالجة إرسال نموذج الحذف
document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري...';
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            closeDeleteModal();
            location.reload();
        } else {
            alert('❌ ' + (data.message || 'حدث خطأ'));
        }
    })
    .catch(() => alert('❌ فشل الاتصال'))
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// إغلاق المودالات عند النقر خارجها
document.querySelectorAll('#viewModal, #deleteModal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeViewModal();
            closeDeleteModal();
        }
    });
});

// إغلاق المودالات بزر Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
        closeDeleteModal();
    }
});
</script>