<?php
namespace App\Factories;

use App\Factories\Interfaces\FormFactoryInterface;
use App\Factories\Forms\SurveyForm;
class SurveyFormFactory implements FormFactoryInterface {
    public function createForm(): \App\Factories\Interfaces\FormInterface {
        return new SurveyForm();
    }
}
