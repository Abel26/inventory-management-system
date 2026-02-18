<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreMoldModificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For now, allow all authenticated users
        // TODO: Implement proper role-based authorization
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asset_model_id' => 'required|exists:asset_models,id',
            'spec_before' => 'required|string|max:255',
            'spec_after' => 'required|string|max:255',
            'production_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'asset_model_id.required' => 'Model cetakan harus dipilih',
            'asset_model_id.exists' => 'Model cetakan tidak valid',
            'spec_before.required' => 'Spesifikasi sebelum harus diisi',
            'spec_before.max' => 'Spesifikasi sebelum maksimal 255 karakter',
            'spec_after.required' => 'Spesifikasi setelah harus diisi',
            'spec_after.max' => 'Spesifikasi setelah maksimal 255 karakter',
            'production_date.required' => 'Tanggal produksi harus diisi',
            'production_date.date' => 'Format tanggal produksi tidak valid',
            'production_date.after' => 'Tanggal produksi harus setelah hari ini',
            'description.max' => 'Deskripsi maksimal 1000 karakter'
        ];
    }
}