<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'picture' => 'string|nullable',
            'category' => 'Required|exists:categories,id',
            'priority' => 'Required|exists:priorities,id',
            'status' => 'Required|exists:statuses,id',
            'created_at' => 'date|nullable',
            'completed_at' => 'date|nullable',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id'
        ];
    }
}