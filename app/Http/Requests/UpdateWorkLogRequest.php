<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $workLog = $this->route('work_log');
        
        // User can edit their own work logs
        if (auth()->check() && auth()->user()->can('Update own work logs')) {
            return auth()->user()->id === $workLog->user_id;
        }
        
        // Or user with permission to update any work logs
        return auth()->check() && auth()->user()->can('Update any work logs');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:1000'],
            'work_date' => ['required', 'date', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'break_duration' => ['nullable', 'integer', 'min:0', 'max:480'],
            'status' => ['nullable', 'string', Rule::in(\App\Enums\WorkStatus::values())],
            'priority' => ['nullable', 'string', Rule::in(\App\Enums\WorkPriority::values())],
            'work_type' => ['nullable', 'string', Rule::in(\App\Enums\WorkType::values())],
            'completion_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'location_id' => ['nullable', 'integer', 'exists:gedungs,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'description.required' => __('work_logs.validation.description_required'),
            'description.max' => __('work_logs.validation.description_max'),
            'work_date.required' => __('work_logs.validation.work_date_required'),
            'work_date.date_format' => __('work_logs.validation.work_date_format'),
            'start_time.required' => __('work_logs.validation.start_time_required'),
            'start_time.date_format' => __('work_logs.validation.start_time_format'),
            'end_time.required' => __('work_logs.validation.end_time_required'),
            'end_time.date_format' => __('work_logs.validation.end_time_format'),
            'end_time.after' => __('work_logs.validation.end_time_after'),
            'break_duration.integer' => __('work_logs.validation.break_duration_integer'),
            'break_duration.min' => __('work_logs.validation.break_duration_min'),
            'break_duration.max' => __('work_logs.validation.break_duration_max'),
            'status.in' => __('work_logs.validation.status_in'),
            'priority.in' => __('work_logs.validation.priority_in'),
            'work_type.in' => __('work_logs.validation.work_type_in'),
            'completion_percentage.integer' => __('work_logs.validation.completion_percentage_integer'),
            'completion_percentage.min' => __('work_logs.validation.completion_percentage_min'),
            'completion_percentage.max' => __('work_logs.validation.completion_percentage_max'),
            'location_id.integer' => __('work_logs.validation.location_id_integer'),
            'location_id.exists' => __('work_logs.validation.location_id_exists'),
            'notes.max' => __('work_logs.validation.notes_max'),
        ];
    }
}
