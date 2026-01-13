<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAssetModelRequest extends FormRequest
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
        $modelId = $this->route('id');

        return [
            'model_code' => 'nullable|string|max:50|unique:asset_models,model_code,' . $modelId,
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'material_id' => 'nullable|exists:asset_materials,id',
            'manufactured_date' => 'nullable|date',
            'condition' => 'required|in:Good,Repair,Damaged',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
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
            'model_code.unique' => 'Kode model sudah digunakan',
            'name.required' => 'Nama model wajib diisi',
            'type.required' => 'Tipe model wajib diisi',
            'material_id.exists' => 'Material tidak ditemukan',
            'condition.required' => 'Kondisi wajib dipilih',
            'condition.in' => 'Kondisi tidak valid',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
