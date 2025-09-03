<?php
namespace App\Factories\Forms;
use App\Factories\Interfaces\FormInterface;
class SurveyForm implements FormInterface {
    public function render(): string {
        return view('forms.survey')->render();
    }
    public function submit(array $data): bool {
        \DB::table('surveys')->insert($data);
        return true;
    }
}
