<?php

return [
    // Model Names (Singular & Plural)
    'hospital_force' => 'قوة المستشفى',
    'hospital_forces' => 'قوات المستشفى',
    'personnel' => 'فرد',
    'all_personnel' => 'كافة الأفراد',
    'department' => 'قسم',
    'departments' => 'الأقسام',
    'violation_type' => 'نوع المخالفة',
    'violation_types' => 'أنواع المخالفات',
    'personnel_violation' => 'مخالفة فرد',
    'personnel_violations' => 'مخالفات الأفراد',
    'leave_type' => 'نوع الإجازة',
    'leave_types' => 'أنواع الإجازات',
    'personnel_leave' => 'إجازة فرد',
    'personnel_leaves' => 'إجازات الأفراد',
    'user' => 'مستخدم',
    'users' => 'المستخدمون',
    'role' => 'دور',
    'roles' => 'الأدوار',

    // Statuses
    'requested' => 'تم الطلب',
    'approved' => 'تمت الموافقة',
    'rejected' => 'مرفوض',
    'taken' => 'تم الحصول عليها', // Or 'مُستنفذة'

    // Roles
    'admin' => 'مدير النظام',
    'military_affairs_officer' => 'ضابط شؤون العسكريين',
    'civilian_affairs_officer' => 'ضابط شؤون المدنيين',

    // General UI Labels / Common terms
    'actions' => 'الإجراءات',
    'edit' => 'تعديل',
    'delete' => 'حذف',
    'create_new' => 'إنشاء جديد',
    'save' => 'حفظ',
    'cancel' => 'إلغاء',
    'details' => 'التفاصيل',
    'search' => 'بحث',
    'filter' => 'تصفية',
    'no_data_available' => 'لا توجد بيانات متاحة',
    'confirm_delete' => 'هل أنت متأكد أنك تريد الحذف؟',
    'yes' => 'نعم',
    'no' => 'لا',
    'name' => 'الاسم',
    'select_option' => 'اختر...',
    'select_option_optional' => 'اختر (اختياري)...',
    'arabic' => 'العربية',
    'english' => 'الإنجليزية',
    'current' => 'الحالي',
    'created_successfully' => 'تم الإنشاء بنجاح',
    'updated_successfully' => 'تم التحديث بنجاح',
    'deleted_successfully' => 'تم الحذف بنجاح',
    'leave_details' => 'تفاصيل الإجازة',
    'requested_on' => 'تاريخ الطلب',
    'back_to_list' => 'العودة إلى القائمة',
    'leave_type_not_applicable' => 'نوع الإجازة هذا غير قابل للتطبيق على الفرد المحدد.',
    'leave_not_in_requested_state' => 'طلب الإجازة ليس في حالة "تم الطلب".',
    'leave_approved_successfully' => 'تمت الموافقة على طلب الإجازة بنجاح.',
    'leave_rejected_successfully' => 'تم رفض طلب الإجازة بنجاح.',
    'leave_not_approved_for_permit' => 'لا يمكن إنشاء تصريح إجازة لطلب لم تتم الموافقة عليه.',
    'none' => 'لا يوجد',


    // LeaveType specific
    'all' => 'الكل', // For applies_to
    'military' => 'عسكري',
    'civilian' => 'مدني',
    'specific_rank' => 'رتبة محددة',
    'specific_job_title' => 'مسمى وظيفي محدد',
    'leave_type_specific_rank_or_title_hint' => 'يُستخدم فقط إذا تم اختيار "رتبة محددة" أو "مسمى وظيفي محدد".',
    'is_permission_hint' => 'هل هذا النوع هو "إذن" وليس إجازة اعتيادية؟',


    // Report Specific
    'daily_eligible_for_leave_report' => 'تقرير الأفراد المستحقين للإجازة اليومية',
    'leave_permit' => 'تصريح الإجازة',
    'period_leave_report' => 'تقرير الإجازات خلال فترة',
    'period_violation_report' => 'تقرير المخالفات خلال فترة',
    'start_date' => 'تاريخ البدء',
    'end_date' => 'تاريخ الانتهاء',
    'generate_report' => 'إنشاء التقرير',
    'for_selected_period' => 'للفترة المحددة',
    'please_select_period_to_generate_report' => 'يرجى تحديد الفترة لإنشاء التقرير.',
    'officer_signature' => 'توقيع الضابط المختص',
    'commander_signature' => 'توقيع القائد',
    'generated_on' => 'تم الإنشاء في',
    'print' => 'طباعة',
];
