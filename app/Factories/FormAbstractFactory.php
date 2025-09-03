<?php
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
