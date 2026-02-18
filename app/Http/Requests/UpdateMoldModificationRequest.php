<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMoldModificationRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'asset_model_id' => 'required|exists:asset_models,id',
            'spec_before' => 'required|string',
            'spec_after' => 'required|string',
            'production_date' => 'required|date',
            'description' => 'nullable|string'
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'asset_model_id.required' => 'Model cetakan wajib dipilih.',
            'asset_model_id.exists' => 'Model cetakan yang dipilih tidak valid.',
            'spec_before.required' => 'Spesifikasi sebelum modifikasi wajib diisi.',
            'spec_after.required' => 'Spesifikasi setelah modifikasi wajib diisi.',
            'production_date.required' => 'Tanggal produksi wajib diisi.',
            'production_date.date' => 'Format tanggal produksi tidak valid.',
        ];
    }
}