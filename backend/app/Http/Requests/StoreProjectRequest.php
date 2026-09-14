<?php

namespace App\Http\Requests;

use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'client_name' => $this->input('client_name', $this->input('clientName')),
            'project_name' => $this->input('project_name', $this->input('projectName')),
            'start_date' => $this->input('start_date', $this->input('startDate')),
            'due_date' => $this->input('due_date', $this->input('dueDate')),
        ]);
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'project_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'priority' => ['required', Rule::enum(ProjectPriority::class)],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];

    }

    public function messages(): array
    {
        return [
            'due_date.after_or_equal' => 'The due date must be on or after the start date.',
        ];
    }
}
