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

    'accepted' => 'يجب قبول الحقل :attribute.',
    'accepted_if' => 'يجب قبول الحقل :attribute عندما يكون :other هو :value.',
    'active_url' => 'الحقل :attribute ليس عنوان URL صالحًا.',
    'after' => 'يجب أن يكون الحقل :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون الحقل :attribute تاريخًا بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي الحقل :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي الحقل :attribute على أحرف وأرقام وشرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي الحقل :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون الحقل :attribute مصفوفة.',
    'before' => 'يجب أن يكون الحقل :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون الحقل :attribute تاريخًا قبل أو يساوي :date.',
    'between' => [
        'array' => 'يجب أن يحتوي الحقل :attribute بين :min و :max عنصرًا.',
        'file' => 'يجب أن يكون الحقل :attribute بين :min و :max كيلوبايت.',
        'numeric' => 'يجب أن يكون الحقل :attribute بين :min و :max.',
        'string' => 'يجب أن يكون الحقل :attribute بين :min و :max حرفًا.',
    ],
    'boolean' => 'يجب أن يكون الحقل :attribute صحيحًا أو خاطئًا.',
    'confirmed' => 'تأكيد الحقل :attribute غير متطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'الحقل :attribute ليس تاريخًا صالحًا.',
    'date_equals' => 'يجب أن يكون الحقل :attribute تاريخًا يساوي :date.',
    'date_format' => 'الحقل :attribute لا يتطابق مع الشكل :format.',
    'declined' => 'يجب رفض الحقل :attribute.',
    'declined_if' => 'يجب أن يكون :attribute مرفوضًا عندما يكون :other :value.',
    'different' => 'يجب أن يكون :attribute أقل من :max أرقام.',
    'mimes' => 'يجب أن يكون :attribute ملف من نوع :values.',
    'mimetypes' => 'يجب أن يكون :attribute ملف من نوع :values.',
    'min' => [
        'array' => 'يجب أن يحتوي :attribute على الأقل على :min عناصر.',
        'file' => 'يجب أن يكون حجم :attribute على الأقل :min كيلوبايت.',
        'numeric' => 'يجب أن يكون :attribute على الأقل :min.',
        'string' => 'يجب أن يكون طول :attribute على الأقل :min أحرف.',
    ],
    'min_digits' => 'يجب أن يكون :attribute على الأقل :min أرقام.',
    'not_in' => 'القيمة المحددة لـ :attribute غير صحيحة.',
    'not_regex' => 'صيغة :attribute غير صحيحة.',
    'numeric' => 'يجب أن يكون :attribute عدداً.',
    'password' => 'كلمة المرور غير صحيحة.',
    'passwords_compare' => 'كلمتا المرور غير متطابقتان.',
    'phone' => 'يجب أن يكون :attribute رقم هاتف صحيح.',
    'present' => 'يجب تقديم الحقل :attribute.',
    'regex' => 'صيغة :attribute غير صحيحة.',
    'required' => 'يجب تعبئة حقل :attribute.',
    'required_if' => 'يجب تعبئة حقل :attribute عندما يكون :other يساوي :value.',
    'required_unless' => 'يجب تعبئة حقل :attribute ما لم يكن :other يساوي :values.',
    'required_with' => 'يجب تعبئة حقل :attribute عندما يكون :values موجود.',
    'required_with_all' => 'يجب تعبئة حقل :attribute عندما يكون :values موجود.',
    'required_without' => 'يجب تعبئة حقل :attribute عندما لا يكون :values موجود.',
    'required_without_all' => 'يجب تعبئة حقل :attribute عندما لا يوجد أي من :values موجود.',
    'same' => 'يجب أن يتطابق :attribute و :other.',
    'size' => [
        'array' => 'يجب أن يحتوي :attribute على :size عنصر.',
        'file' => 'يجب أن يكون حجم ملف :attribute :size كيلوبايت.',
        'numeric' => 'يجب أن يكون :attribute :size.',
        'string' => 'يجب أن يكون :attribute :size أحرف.',
    ],
    'starts_with' => 'يجب أن يبدأ :attribute بأحد القيم التالية: :values.',
    'string' => 'يجب أن يكون :attribute نصاً.',
    'timezone' => 'يجب أن يكون :attribute منطقة صالحة.',
    'unique' => 'قيمة :attribute مُستخدمة من قبل.',
    'uploaded' => 'فشل في تحميل :attribute.',
    'url' => 'صيغة :attribute غير صحيحة.',
    'uuid' => 'يجب أن يكون :attribute رقم UUID صالح.',

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


    'attributes' => [],

];
