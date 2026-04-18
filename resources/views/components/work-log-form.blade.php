@props([
    'isEdit' => false,
    'workLog' => null,
    'users' => []
])

<form id="work-log-form" action="{{ $isEdit ? route('work-logs.update', $workLog->id) : route('work-logs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="space-y-8">
        @if(count($users) > 0)
        <!-- Employee Selection (Admin Only) -->
        <div class="space-y-2">
            <label for="user_id" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                <i class="ph ph-user-circle text-indigo-600"></i>
                {{ __('work_logs.fields.user') }}
                <span class="text-rose-500">*</span>
            </label>
            <select
                id="user_id"
                name="user_id"
                class="mt-1 block w-full rounded-2xl border-slate-200 bg-indigo-50/30 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white sm:text-sm transition-all duration-200 font-bold text-slate-700"
                required
            >
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ ($isEdit && $workLog && $workLog->user_id == $user->id) || (!$isEdit && Auth::id() == $user->id) ? 'selected' : '' }}>
                        {{ $user->full_name }}
                    </option>
                @endforeach
            </select>
            <p class="text-[10px] font-bold text-slate-400 italic">Admin dapat mencatatkan pekerjaan bagi pegawai lain.</p>
        </div>
        @endif
        <!-- Description -->
        <div class="space-y-2">
            <label for="description" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                <i class="ph ph-chat-centered-text text-indigo-600"></i>
                {{ __('work_logs.fields.description') }}
                <span class="text-rose-500">*</span>
            </label>
            <textarea
                id="description"
                name="description"
                rows="4"
                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white sm:text-sm transition-all duration-200 placeholder:text-slate-400 placeholder:font-medium"
                required
                placeholder="{{ __('work_logs.placeholders.description') }}"
            >{{ $isEdit && $workLog ? old('description', $workLog->description) : old('description') }}</textarea>
            @if($errors->has('description'))
                <p class="mt-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                    <i class="ph ph-warning-circle"></i>
                    {{ $errors->first('description') }}
                </p>
            @endif
        </div>

        <!-- Date & Time Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 bg-slate-50/50 rounded-3xl border border-slate-100">
            <!-- Work Date -->
            <div class="space-y-2">
                <label for="work_date" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                    <i class="ph ph-calendar-blank text-indigo-600"></i>
                    {{ __('work_logs.fields.work_date') }}
                    <span class="text-rose-500">*</span>
                </label>
                <input
                    type="date"
                    id="work_date"
                    name="work_date"
                    value="{{ $isEdit && $workLog ? old('work_date', $workLog->work_date?->format('Y-m-d')) : old('work_date', now()->toDateString()) }}"
                    class="mt-1 block w-full rounded-2xl border-slate-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 font-bold text-slate-700"
                    required
                />
                @if($errors->has('work_date'))
                    <p class="mt-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i>
                        {{ $errors->first('work_date') }}
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="start_time" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-clock text-indigo-600"></i>
                        {{ __('work_logs.fields.start_time') }}
                    </label>
                    <input
                        type="text"
                        id="start_time"
                        name="start_time"
                        value="{{ $isEdit && $workLog ? old('start_time', $workLog->start_time?->format('H:i')) : old('start_time') }}"
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 font-bold text-slate-700 time-picker"
                        required
                        placeholder="00:00"
                    />
                </div>

                <div class="space-y-2">
                    <label for="end_time" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-clock-afternoon text-indigo-600"></i>
                        {{ __('work_logs.fields.end_time') }}
                    </label>
                    <input
                        type="text"
                        id="end_time"
                        name="end_time"
                        value="{{ $isEdit && $workLog ? old('end_time', $workLog->end_time?->format('H:i')) : old('end_time') }}"
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 font-bold text-slate-700 time-picker"
                        required
                        placeholder="00:00"
                    />
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Break & Completion -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="break_duration" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-coffee text-indigo-600"></i>
                        {{ __('work_logs.fields.break_duration') }}
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            id="break_duration"
                            name="break_duration"
                            value="{{ $isEdit && $workLog ? old('break_duration', $workLog->break_duration) : old('break_duration', 0) }}"
                            min="0"
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white sm:text-sm transition-all duration-200 font-bold text-slate-700 pr-12"
                        />
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">MIN</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <label for="completion_percentage" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-chart-pie-slice text-indigo-600"></i>
                            {{ __('work_logs.fields.completion_percentage') }}
                        </span>
                        <span id="completion-value" class="text-indigo-600 bg-indigo-50 px-3 py-1 rounded-xl text-xs font-black ring-1 ring-indigo-200">{{ $isEdit && $workLog ? old('completion_percentage', $workLog->completion_percentage ?? 0) : old('completion_percentage', 0) }}%</span>
                    </label>
                    <div class="relative mt-2 px-1">
                        <input
                            type="range"
                            id="completion_percentage"
                            name="completion_percentage"
                            value="{{ $isEdit && $workLog ? old('completion_percentage', $workLog->completion_percentage ?? 0) : old('completion_percentage', 0) }}"
                            min="0"
                            max="100"
                            class="w-full h-2 bg-slate-100 rounded-full appearance-none cursor-pointer accent-indigo-600"
                            oninput="document.getElementById('completion-value').textContent = this.value + '%'"
                        />
                        <div class="flex justify-between text-[10px] font-black text-slate-400 mt-2 uppercase tracking-tighter">
                            <span>Started</span>
                            <span>Halfway</span>
                            <span>Finished</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categorization -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="priority" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-shield-star text-indigo-600"></i>
                        {{ __('work_logs.fields.priority') }}
                    </label>
                    <select
                        id="priority"
                        name="priority"
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white sm:text-sm transition-all duration-200 font-bold text-slate-700"
                    >
                        <option value="">{{ __('work_logs.select.select_priority') }}</option>
                        @foreach(\App\Enums\WorkPriority::cases() as $priority)
                            <option value="{{ $priority->value }}" {{ ($isEdit && $workLog && old('priority', $workLog->priority->value) == $priority->value) || (!$isEdit && old('priority') == $priority->value) ? 'selected' : '' }}>
                                {{ $priority->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="status" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-selection-all text-indigo-600"></i>
                        {{ __('work_logs.fields.status') }}
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white sm:text-sm transition-all duration-200 font-bold text-slate-700"
                    >
                        <option value="">{{ __('work_logs.select.select_status') }}</option>
                        @foreach(\App\Enums\WorkStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ ($isEdit && $workLog && old('status', $workLog->status->value) == $status->value) || (!$isEdit && old('status') == $status->value) ? 'selected' : '' }}>
                                {{ $status->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Work Type & Location -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label for="work_type" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                    <i class="ph ph-briefcase text-indigo-600"></i>
                    {{ __('work_logs.fields.work_type') }}
                </label>
                <select
                    id="work_type"
                    name="work_type"
                    class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white sm:text-sm transition-all duration-200 font-bold text-slate-700"
                >
                    <option value="">{{ __('work_logs.select.select_type') }}</option>
                    @foreach(\App\Enums\WorkType::cases() as $type)
                        <option value="{{ $type->value }}" {{ ($isEdit && $workLog && old('work_type', $workLog->work_type->value) == $type->value) || (!$isEdit && old('work_type') == $type->value) ? 'selected' : '' }}>
                            {{ $type->getLabel() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label for="location_id" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                    <i class="ph ph-map-pin text-indigo-600"></i>
                    {{ __('work_logs.fields.location') }}
                </label>
                <select
                    id="location_id"
                    name="location_id"
                    class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white sm:text-sm transition-all duration-200 font-bold text-slate-700"
                >
                    <option value="">{{ __('work_logs.select.select_location') }}</option>
                    @foreach(\App\Models\Gedung::all() as $location)
                        <option value="{{ $location->id }}" {{ ($isEdit && $workLog && old('location_id', $workLog->location_id) == $location->id) || (!$isEdit && old('location_id') == $location->id) ? 'selected' : '' }}>
                            {{ $location->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Attachment -->
        <div class="space-y-2">
            <label for="attachment" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                <i class="ph ph-paperclip text-indigo-600"></i>
                {{ __('work_logs.fields.attachment') ?? 'Lampiran Bukti Kerja' }}
            </label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-[2rem] bg-slate-50/50 hover:bg-white hover:border-indigo-300 transition-all group">
                <div class="space-y-1 text-center">
                    <i class="ph ph-cloud-arrow-up text-4xl text-slate-300 group-hover:text-indigo-400 mb-2 transition-colors"></i>
                    <div class="flex text-sm text-slate-600">
                        <label for="attachment" class="relative cursor-pointer bg-white rounded-md font-black text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                            <span>Unggah berkas</span>
                            <input id="attachment" name="attachment" type="file" class="sr-only">
                        </label>
                        <p class="pl-1">atau drag and drop</p>
                    </div>
                    <p class="text-xs text-slate-400 font-bold">
                        PNG, JPG, PDF up to 2MB
                    </p>
                    @if($isEdit && $workLog && $workLog->attachment_path)
                        <div class="mt-3 p-2 bg-indigo-50 rounded-xl flex items-center justify-center gap-2 border border-indigo-100">
                            <i class="ph ph-file text-indigo-500"></i>
                            <span class="text-xs font-black text-indigo-700">{{ $workLog->attachment_name }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @if($errors->has('attachment'))
                <p class="mt-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                    <i class="ph ph-warning-circle"></i>
                    {{ $errors->first('attachment') }}
                </p>
            @endif
        </div>

        <!-- Notes -->
        <div class="space-y-2">
            <label for="notes" class="block text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                <i class="ph ph-pencil-line text-indigo-600"></i>
                {{ __('work_logs.fields.notes') }}
            </label>
            <textarea
                id="notes"
                name="notes"
                rows="3"
                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white sm:text-sm transition-all duration-200 placeholder:text-slate-400 font-medium"
                placeholder="{{ __('work_logs.placeholders.notes') }}"
            >{{ $isEdit && $workLog ? old('notes', $workLog->notes) : old('notes') }}</textarea>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-8 border-t border-slate-100 mt-4">
            <a href="{{ $isEdit ? route('work-logs.show', $workLog->id) : route('work-logs.my-work') }}"
               class="inline-flex items-center px-6 py-3 border-2 border-slate-200 rounded-2xl text-sm font-black text-slate-500 shadow-sm hover:bg-slate-50 hover:text-slate-700 focus:outline-none transition-all duration-200 active:scale-95 uppercase tracking-widest">
                {{ __('work_logs.buttons.cancel') }}
            </a>
            <button type="submit"
                    class="inline-flex items-center px-8 py-3 border border-transparent rounded-2xl shadow-xl shadow-indigo-200 text-sm font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all duration-200 active:scale-95 uppercase tracking-widest">
                <i class="ph ph-rocket-launch text-xl mr-2"></i>
                {{ $isEdit ? __('work_logs.buttons.update') : __('work_logs.buttons.save') }}
            </button>
        </div>
    </div>
</form>
