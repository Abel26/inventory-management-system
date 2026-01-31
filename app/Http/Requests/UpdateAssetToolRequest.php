<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAssetToolRequest extends FormRequest
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
        $toolId = $this->route('id');

        return [
            'tool_code' => 'required|string|max:50|unique:asset_tools,tool_code,' . $toolId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:hand_tools,power_tools,measuring,other',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:Good,Repair,Damaged,Disposed',
            'last_maintenance' => 'nullable|date',
            'next_maintenance' => 'nullable|date|after_or_equal:last_maintenance',
            'notes' => 'nullable|string',
            // File upload validation - Security hardening
            'image' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf',
            'documents.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tool_code.required' => 'Tool code is required',
            'tool_code.unique' => 'Tool code already exists',
            'name.required' => 'Tool name is required',
            'category.required' => 'Category is required',
            'category.in' => 'Invalid category selected',
            'brand.max' => 'Brand may not be greater than 255 characters',
            'type.max' => 'Type may not be greater than 255 characters',
            'condition.required' => 'Condition is required',
            'condition.in' => 'Invalid condition selected',
            'purchase_date.required' => 'Purchase date is required',
            'purchase_date.date' => 'Purchase date must be a valid date',
            'purchase_price.required' => 'Purchase price is required',
            'purchase_price.numeric' => 'Purchase price must be a number',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a number',
            'quantity.min' => 'Quantity must be at least 1',
            'next_maintenance.after_or_equal' => 'Next maintenance date must be after or equal to last maintenance date',
        ];
    }

    // Remove failedValidation method to let Laravel handle redirects naturally
    // This is better for HTML forms while still working with AJAX
}
