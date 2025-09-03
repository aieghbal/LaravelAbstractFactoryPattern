<?php
namespace App\Factories\Forms;

use App\Factories\Interfaces\FormInterface;

class ContactForm implements FormInterface {
    public function render(): string {
        return view('forms.contact')->render();
    }
    public function submit(array $data): bool {
        // مثال: ارسال ایمیل
        Mail::to('admin@example.com')->send(new ContactMail($data));
        return true;
    }
}
