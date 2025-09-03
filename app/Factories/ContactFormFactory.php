<?php
namespace App\Factories;

use App\Factories\Interfaces\FormFactoryInterface;
use App\Factories\Forms\ContactForm;

class ContactFormFactory implements FormFactoryInterface {
    public function createForm(): \App\Factories\Interfaces\FormInterface {
        return new ContactForm();
    }
}
