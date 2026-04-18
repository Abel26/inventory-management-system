<x-app-layout>
    <x-slot name="title">{{ __('work_logs.page.edit_title', ['code' => $workLog->work_code]) }}</x-slot>

<div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-10 text-center sm:text-left">
            <a href="{{ request()->has('from') && request()->input('from') == 'my-work' ? route('work-logs.my-work') : route('work-logs.show', $workLog->id) }}"
               class="inline-flex items-center text-xs font-black text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest mb-6 group">
                <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <i class="ph ph-caret-left text-lg"></i>
                </div>
                {{ __('work_logs.actions.back') }}
            </a>
            
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none mb-3">
                        {{ __('work_logs.page.edit_title', ['code' => $workLog->work_code]) }}
                    </h1>
                    <p class="text-slate-500 font-bold text-lg flex items-center justify-center sm:justify-start gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        {{ __('work_logs.page.edit_description') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert if locked -->
        @if($workLog->isLocked())
        <div class="mb-8 bg-amber-50 border-2 border-amber-200 rounded-3xl p-6 shadow-sm flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                <i class="ph ph-lock-key-open text-2xl animate-pulse"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-amber-900 uppercase tracking-widest mb-1">
                    {{ __('work_logs.messages.locked_warning') }}
                </h3>
                <p class="text-sm font-bold text-amber-800/80">
                    {{ __('work_logs.messages.locked_description') }}
                </p>
            </div>
        </div>
        @endif

        <!-- Edit Form Card -->
        <div class="bg-white shadow-2xl shadow-indigo-100 rounded-[2.5rem] border border-slate-100 p-8 sm:p-12 relative overflow-hidden {{ $workLog->isLocked() ? 'opacity-80 grayscale-[0.2]' : '' }}">
            <!-- Decorative Element -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/30 -mr-32 -mt-32 rounded-full pointer-events-none"></div>

            <div class="relative">
                <x-work-log-form 
                    :isEdit="true"
                    :workLog="$workLog"
                    :users="$users"
                />
            </div>
        </div>
    </div>
</div>

<script>
// Handle form submission via AJAX
document.getElementById('work-log-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ __('work_logs.actions.saving') }}...
    `;

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '{{ __('modules.swal.success') }}',
                text: data.message || '{{ __('work_logs.messages.updated') }}',
                confirmButtonColor: '#009B77',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '{{ route('work-logs.show', $workLog->id) }}';
            });
        } else {
            let errorMsg = data.message || '{{ __('work_logs.messages.update_failed') }}';
            
            // If there are validation errors, format them into a list
            if (data.errors) {
                errorMsg = `<div class="text-left mt-2">
                    <p class="font-bold mb-2">${data.message || 'Harap perbaiki kesalahan berikut:'}</p>
                    <ul class="list-disc list-inside text-sm space-y-1 text-rose-600">
                        ${Object.values(data.errors).map(errs => errs.map(err => `<li>${err}</li>`).join('')).join('')}
                    </ul>
                </div>`;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMsg,
                confirmButtonColor: '#dc2626'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ __('work_logs.messages.update_failed') }}',
            confirmButtonColor: '#dc2626'
        });
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
</x-app-layout>
