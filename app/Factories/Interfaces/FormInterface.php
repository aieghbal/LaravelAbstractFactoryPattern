<?php
namespace App\Factories\Interfaces;

interface FormInterface {
    public function render(): string;
    public function submit(array $data): bool;
}
