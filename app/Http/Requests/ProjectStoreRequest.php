<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isManager() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:' . implode(',', Project::STATUSES)],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
            'members' => ['array'],
            'members.*' => ['integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nazwa projektu jest wymagana.',
            'name.min' => 'Nazwa musi mieć co najmniej 3 znaki.',
            'deadline.after_or_equal' => 'Deadline nie może być w przeszłości.',
        ];
    }
}
