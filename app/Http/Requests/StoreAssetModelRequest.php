<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetModelRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'material_id' => 'nullable|integer|exists:asset_materials,id',
            'manufacture_date' => 'nullable|date|before_or_equal:today',
            'condition' => 'required|string|in:Good,Repair,Damaged',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
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
            'name.required' => 'Nama model wajib diisi',
            'name.max' => 'Nama model maksimal 255 karakter',
            'type.required' => 'Tipe model wajib diisi',
            'type.max' => 'Tipe model maksimal 100 karakter',
            'material_id.integer' => 'Material harus berupa angka',
            'material_id.exists' => 'Material tidak ditemukan',
            'manufacture_date.date' => 'Tanggal pembuatan harus berupa tanggal yang valid',
            'manufacture_date.before_or_equal' => 'Tanggal pembuatan tidak boleh melebihi hari ini',
            'condition.required' => 'Kondisi wajib dipilih',
            'condition.in' => 'Kondisi tidak valid',
            'location.max' => 'Lokasi maksimal 255 karakter',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
        ];
    }
}
