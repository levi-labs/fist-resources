<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'username' => 'required|min:3',
            'email' => 'required|email',
            'role' => 'required|in:admin,staff,procurement,logistic',
        ];
        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|min:4';
        }
        if ($this->isMethod('PUT')) {
            $rules['password'] = 'nullable|min:4';
        }
        return $rules;
    }
}
