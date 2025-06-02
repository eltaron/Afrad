<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول السمة :attribute.',
    'active_url' => 'السمة :attribute ليست عنوان URL صالحًا.',
    'after' => 'يجب أن يكون :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون :attribute تاريخًا بعد أو يساوي :date.',
    'alpha' => 'يجب أن تحتوي السمة :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن تحتوي السمة :attribute على أحرف وأرقام وشرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن تحتوي السمة :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن تكون السمة :attribute مصفوفة.',
    'before' => 'يجب أن يكون :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون :attribute تاريخًا قبل أو يساوي :date.',
    // ... (other rules can be added here, translating a few common ones)
    'date' => ':attribute ليس تاريخًا صالحًا.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالحًا.',
    'exists' => ':attribute المحدد غير صالح.',
    'file' => 'يجب أن يكون :attribute ملفًا.',
    'image' => 'يجب أن يكون :attribute صورة.',
    'in' => ':attribute المحدد غير صالح.',
    'integer' => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'max' => [
        'numeric' => 'يجب ألا يزيد :attribute عن :max.',
        'file' => 'يجب ألا يزيد حجم الملف :attribute عن :max كيلوبايت.',
        'string' => 'يجب ألا يزيد طول النص :attribute عن :max حروف.',
        'array' => 'يجب ألا تحتوي المصفوفة :attribute على أكثر من :max عناصر.',
    ],
    'min' => [
        'numeric' => 'يجب أن يكون :attribute على الأقل :min.',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :attribute على الأقل :min حروف.',
        'array' => 'يجب أن تحتوي المصفوفة :attribute على الأقل على :min عناصر.',
    ],
    'not_in' => ':attribute المحدد غير صالح.',
    'numeric' => 'يجب أن يكون :attribute رقمًا.',
    'password' => 'كلمة المرور غير صحيحة.',
    'present' => 'يجب تقديم الحقل :attribute.',
    'regex' => 'تنسيق :attribute غير صالح.',
    'required' => 'حقل :attribute مطلوب.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عندما تكون :values موجودة.',
    'required_with_all' => 'حقل :attribute مطلوب عندما تكون :values موجودة.',
    'required_without' => 'حقل :attribute مطلوب عندما لا تكون :values موجودة.',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا تكون أي من :values موجودة.',
    'same' => 'يجب أن يتطابق :attribute و :other.',
    'size' => [
        'numeric' => 'يجب أن يكون :attribute بحجم :size.',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :attribute :size حروف.',
        'array' => 'يجب أن تحتوي المصفوفة :attribute على :size عناصر.',
    ],
    'string' => 'يجب أن يكون :attribute نصًا.',
    'timezone' => 'يجب أن يكون :attribute منطقة زمنية صالحة.',
    'unique' => 'قيمة :attribute مُستخدمة من قبل.',
    'uploaded' => 'فشل تحميل :attribute.',
    'url' => 'تنسيق :attribute غير صالح.',
    'uuid' => 'يجب أن يكون :attribute UUID صالحًا.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'الاسم',
        'name_ar' => 'الاسم (بالعربية)',
        'name_en' => 'الاسم (بالانجليزية)',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'role' => 'الدور',
        'description' => 'الوصف',
        'description_ar' => 'الوصف (بالعربية)',
        'description_en' => 'الوصف (بالانجليزية)',

        'personnel_id' => 'معرف الفرد',
        'leave_type_id' => 'نوع الإجازة',
        'start_date' => 'تاريخ البدء',
        'end_date' => 'تاريخ الانتهاء',
        'notes' => 'ملاحظات',
        'military_id' => 'الرقم العسكري',
        'national_id' => 'الرقم القومي',
        'phone_number' => 'رقم الهاتف',
        'recruitment_date' => 'تاريخ التعيين',
        'termination_date' => 'تاريخ إنهاء الخدمة',
        'job_title' => 'المسمى الوظيفي',
        'rank' => 'الرتبة',
        'hospital_force_id' => 'قوة المستشفى',
        'user_id' => 'معرف المستخدم',
        'department_id' => 'القسم',
        'violation_type_id' => 'نوع المخالفة',
        'violation_date' => 'تاريخ المخالفة',
        'penalty_type' => 'نوع العقوبة',
        'penalty_days' => 'أيام العقوبة',
        'leave_deduction_days' => 'أيام خصم الإجازة',
        'default_days' => 'الأيام الافتراضية',
        'applies_to' => 'تنطبق على',
        'specific_rank_or_title' => 'الرتبة أو المسمى الوظيفي المحدد',
        'is_permission' => 'هل هو إذن',
        'days_taken' => 'الأيام المستغرقة',
        'status' => 'الحالة',
        'approved_by' => 'تمت الموافقة بواسطة',
    ],
];
