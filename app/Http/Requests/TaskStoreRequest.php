<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => ['required', 'string', 'min:3', 'max:150'],
            'description' => ['nullable', 'string', 'max:3000'],
            'status' => ['required', 'in:' . implode(',', Task::STATUSES)],
            'priority' => ['required', 'in:' . implode(',', Task::PRIORITIES)],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'tags' => ['array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'attachments' => ['array', 'max:5'],
            'attachments.*' => ['file', 'max:5120', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tytuł zadania jest wymagany.',
            'title.min' => 'Tytuł musi mieć co najmniej 3 znaki.',
            'attachments.*.max' => 'Plik nie może przekraczać 5 MB.',
            'attachments.*.mimes' => 'Dozwolone typy: jpg, png, gif, pdf, doc, docx, txt, zip.',
        ];
    }
}
