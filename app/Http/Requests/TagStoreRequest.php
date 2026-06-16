<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isManager() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('tag')?->id;
        return [
            'name' => ['required', 'string', 'min:2', 'max:30', 'unique:tags,name,' . ($id ?? 'NULL')],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Tag o tej nazwie już istnieje.',
            'color.regex' => 'Kolor musi być w formacie hex, np. #ff0000.',
        ];
    }
}
