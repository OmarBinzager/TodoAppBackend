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
            'description' => 'text|max:250',
            'picture' => 'string|nullable',
            'category' => 'Required|exists:categories,id',
            'priority' => 'Required|exists:priorities,id',
            'category' => 'Required|exists:categories,id',
            'category' => 'string|nullable|max:200',


        ];
    }
}
