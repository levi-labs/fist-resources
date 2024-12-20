<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProposedProductRequest extends FormRequest
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
            'model' => 'required',
            'brand' => 'required',
            'size' => 'nullable',
            'unit_type' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|unique:products,sku',
            'description' => 'required',
        ];

        if ($this->isMethod('PUT')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048';
        }

        if ($this->isMethod('POST')) {

            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048';
        }
        return $rules;
    }
}
