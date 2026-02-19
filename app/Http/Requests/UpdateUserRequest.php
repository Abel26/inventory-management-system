<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('id');
        
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $userId,
            'password' => 'nullable|string|confirmed|min:1',
            'phone_number' => 'nullable|string|max:20|regex:/^[+]?[0-9\s\-\(\)]+$/',
            'role' => 'required|string|exists:roles,name',
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama user wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'username.alpha_dash' => 'Username hanya boleh mengandung huruf, angka, dash, dan underscore',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 1 karakter',
            'phone_number.regex' => 'Format nomor telepon tidak valid',
            'role.required' => 'Role wajib dipilih',
            'role.exists' => 'Role yang dipilih tidak valid',
            'is_active.required' => 'Status user wajib dipilih',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Prevent user from deactivating themselves
            if ($this->input('is_active') == false && $this->route('id') == Auth::id()) {
                $validator->errors()->add('is_active', 'Anda tidak dapat menonaktifkan akun sendiri');
            }
        });
    }
}