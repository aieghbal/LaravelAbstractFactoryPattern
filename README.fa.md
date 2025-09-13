# 🎯 آموزش الگوی Abstract Factory در لاراول

این پروژه یک مینی‌پروژه آموزشی است که نحوه پیاده‌سازی **الگوی طراحی Abstract Factory** را در فریم‌ورک **Laravel** نمایش می‌دهد. هدف این پروژه، درک بهتر معماری و جداسازی مسئولیت‌ها در زمان ایجاد اشیای پیچیده است.

---

## 📖 محتوای آموزش

* معرفی الگوی Abstract Factory
* طراحی Interfaceها
* پیاده‌سازی کلاس‌های مختلف فرم
* پیاده‌سازی Factoryهای مرتبط
* ایجاد یک Abstract Factory مرکزی
* استفاده در کنترلر و روت‌ها
* نمایش خروجی در ویوها

---

## 🏗️ معرفی الگوی Abstract Factory

> **Abstract Factory** یک الگوی طراحی Creational است که وظیفه دارد **خانواده‌ای از اشیا** را بدون مشخص کردن کلاس دقیق آن‌ها ایجاد کند.

در این پروژه، ما یک سیستم ساده تولید **فرم‌های آنلاین** داریم که هر فرم رفتار و استایل متفاوتی دارد (مثل فرم تماس با ما و فرم نظرسنجی).

---

## 📂 ساختار پوشه‌ها

```
app/
 └── Factories/
      ├── Interfaces/
      │    ├── FormInterface.php
      │    └── FormFactoryInterface.php
      ├── Forms/
      │    ├── ContactForm.php
      │    └── SurveyForm.php
      ├── ContactFormFactory.php
      ├── SurveyFormFactory.php
      └── FormAbstractFactory.php

app/Http/Controllers/
 └── FormController.php

resources/views/forms/
 ├── contact.blade.php
 └── survey.blade.php
```

---

## ✨ مرحله 1: تعریف Interfaceها

```php
namespace App\Factories\Interfaces;

interface FormInterface {
    public function render(): string;
    public function submit(array $data): bool;
}

interface FormFactoryInterface {
    public function createForm(): FormInterface;
}
```

---

## ✨ مرحله 2: پیاده‌سازی فرم‌ها

```php
namespace App\Factories\Forms;

use App\Factories\Interfaces\FormInterface;
use Illuminate\Support\Facades\Mail;

class ContactForm implements FormInterface {
    public function render(): string {
        return view('forms.contact')->render();
    }
    public function submit(array $data): bool {
        Mail::to('admin@example.com')->send(new \App\Mail\ContactMail($data));
        return true;
    }
}

class SurveyForm implements FormInterface {
    public function render(): string {
        return view('forms.survey')->render();
    }
    public function submit(array $data): bool {
        \DB::table('surveys')->insert($data);
        return true;
    }
}
```

---

## ✨ مرحله 3: پیاده‌سازی Factoryها

```php
namespace App\Factories;

use App\Factories\Interfaces\FormFactoryInterface;
use App\Factories\Forms\ContactForm;
use App\Factories\Forms\SurveyForm;

class ContactFormFactory implements FormFactoryInterface {
    public function createForm(): \App\Factories\Interfaces\FormInterface {
        return new ContactForm();
    }
}

class SurveyFormFactory implements FormFactoryInterface {
    public function createForm(): \App\Factories\Interfaces\FormInterface {
        return new SurveyForm();
    }
}
```

---

## ✨ مرحله 4: Abstract Factory

```php
namespace App\Factories;

use App\Factories\Interfaces\FormFactoryInterface;
use Illuminate\Support\Str;

class FormAbstractFactory {
    public static function make(string $type): FormFactoryInterface {
        return match(Str::lower($type)) {
            'contact' => new ContactFormFactory(),
            'survey'  => new SurveyFormFactory(),
            default   => throw new \InvalidArgumentException("Unknown form type: $type"),
        };
    }
}
```

---

## ✨ مرحله 5: کنترلر

```php
namespace App\Http\Controllers;

use App\Factories\FormAbstractFactory;
use Illuminate\Http\Request;

class FormController extends Controller {
    public function show(Request $request, string $type) {
        $form = FormAbstractFactory::make($type)->createForm();
        return response($form->render());
    }

    public function submit(Request $request, string $type) {
        $form = FormAbstractFactory::make($type)->createForm();
        $success = $form->submit($request->all());

        return $success
            ? redirect()->back()->with('status', 'Success!')
            : redirect()->back()->with('error', 'Submission failed.');
    }
}
```

---

## ✨ مرحله 6: تعریف روت‌ها

```php
use App\Http\Controllers\FormController;

Route::get('/form/{type}', [FormController::class, 'show']);
Route::post('/form/{type}', [FormController::class, 'submit']);
```

---

## ✨ مرحله 7: ویوها

**resources/views/forms/contact.blade.php**

```blade
<form method="POST">
    @csrf
    <input name="name" placeholder="Name">
    <input name="email" placeholder="Email">
    <textarea name="message" placeholder="Your message"></textarea>
    <button type="submit">Send</button>
</form>
```

**resources/views/forms/survey.blade.php**

```blade
<form method="POST">
    @csrf
    <label>Rate us: <input type="number" name="rating" min="1" max="5"></label>
    <textarea name="feedback" placeholder="Your feedback"></textarea>
    <button type="submit">Submit</button>
</form>
```

---

## 🚀 نتیجه‌گیری

در این مینی‌پروژه یاد گرفتیم:

* چطور با استفاده از **Interface** و **Abstract Factory** ساخت اشیا را از منطق برنامه جدا کنیم.
* چگونه فرم‌های مختلف با رفتار متفاوت ایجاد کنیم بدون اینکه در کنترلر وابستگی مستقیم به کلاس خاصی داشته باشیم.
* این الگو باعث **کاهش Coupling** و **افزایش انعطاف‌پذیری** در پروژه می‌شود.

---
📄 [English Version](./README.md)  
