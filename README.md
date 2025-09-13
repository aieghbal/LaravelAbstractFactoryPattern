# 🎯 Abstract Factory Pattern Tutorial in Laravel

This project is a small educational project that demonstrates how to implement the Abstract Factory design pattern in the Laravel framework. The goal is to better understand architecture and separation of responsibilities when creating complex objects.

---

## 📖 Tutorial Contents

Introduction to the Abstract Factory pattern

Designing interfaces

Implementing different form classes

Implementing the related factories

Creating a central Abstract Factory

Using it in controllers and routes

Displaying output in views

---

## 🏗️ Introduction to the Abstract Factory Pattern

Abstract Factory is a Creational design pattern that creates families of related objects without specifying their concrete classes.

In this project, we have a simple online form generation system where each form has different behavior and styling (e.g., a Contact Us form and a Survey form).

---

## 📂 Folder Structure

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

## ✨ Step 1: Define the Interfaces

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

## ✨ Step 2: Implement the Forms

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

## ✨ Step 3: Implement the Factories

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

## ✨Step 4: Abstract Factory

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

## ✨ Step 5: Controller

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

## ✨ Step 6: Define the Routes

```php
use App\Http\Controllers\FormController;

Route::get('/form/{type}', [FormController::class, 'show']);
Route::post('/form/{type}', [FormController::class, 'submit']);
```

---

## ✨ Step 7: Views

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

## 🚀 Conclusion

In this mini-project, we learned:

How to use interfaces and Abstract Factory to separate object creation from application logic.

How to create different forms with different behaviors without directly coupling controllers to specific classes.

This pattern helps reduce coupling and increase flexibility in the project.

---
📄 [Persian Version](./README.fa.md) 
