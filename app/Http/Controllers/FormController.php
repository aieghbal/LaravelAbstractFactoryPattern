<?php

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
