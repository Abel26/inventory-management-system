<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAssetMaterialRequest extends FormRequest
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
        $materialId = $this->route('id');

        return [
            'material_code' => 'nullable|string|max:50|unique:asset_materials,material_code,' . $materialId,
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'min_threshold' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'entry_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:entry_date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'unit_price' => 'nullable|numeric|min:0',
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
            'material_code.unique' => 'Kode material sudah digunakan',
            'name.required' => 'Nama material wajib diisi',
            'type.required' => 'Tipe material wajib diisi',
            'quantity.required' => 'Jumlah stok wajib diisi',
            'quantity.integer' => 'Jumlah stok harus berupa angka',
            'quantity.min' => 'Jumlah stok tidak boleh negatif',
            'unit.required' => 'Satuan wajib diisi',
            'min_threshold.required' => 'Batas minimum wajib diisi',
            'entry_date.required' => 'Tanggal masuk wajib diisi',
            'entry_date.date' => 'Format tanggal tidak valid',
            'expiry_date.after_or_equal' => 'Tanggal kedaluwarsa harus setelah atau sama dengan tanggal masuk',
            'unit_price.numeric' => 'Harga satuan harus berupa angka',
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
