@props([
    'tableId' => 'dataTable',
    'minWidth' => '600px',
    'showHeader' => true,
    'showFooter' => true,
    'className' => 'bg-white shadow-lg rounded-xl border border-gray-100 p-6'
])

<div class="{{ $className }}">
    <div class="overflow-x-auto w-full">
        {{ $slot }}
    </div>
</div>

@once
@push('styles')
<style>
    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background-color: white;
        font-size: 0.875rem;
        color: #374151;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #009B77;
        box-shadow: 0 0 0 3px rgba(0, 155, 119, 0.1);
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background-color: white;
        font-size: 0.875rem;
        color: #374151;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 200px;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #009B77;
        box-shadow: 0 0 0 3px rgba(0, 155, 119, 0.1);
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 1rem;
        padding-bottom: 0.5rem;
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 0.5rem;
        padding-bottom: 0;
        margin-bottom: 0;
        display: flex;
        justify-content: flex-end;
        gap: 0.25rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin: 0 2px;
        padding: 6px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background-color: white;
        color: #374151 !important;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #f3f4f6;
        color: #111827 !important;
        border-color: #d1d5db;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #009B77 !important;
        color: white !important;
        border-color: #009B77 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .dataTables_wrapper .dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        margin-left: -100px;
        margin-top: -25px;
        border: 1px solid #ddd;
        text-align: center;
        color: #333;
        font-size: 14px;
        background-color: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    /* Fix table wrapper overflow */
    .dataTables_wrapper {
        width: 100%;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .dataTables_wrapper table {
        width: 100% !important;
        margin-bottom: 0 !important;
    }

    /* Fix excessive spacing from DataTables */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0.5rem !important;
    }

    /* Fix alignment for length and filter */
    .dataTables_wrapper .dataTables_length {
        float: left;
        text-align: left;
    }

    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        font-weight: 500;
        color: #6b7280;
        font-size: 0.875rem;
    }

    /* Clear floats for pagination */
    .dataTables_wrapper:after {
        content: "";
        display: table;
        clear: both;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            float: none;
            text-align: left;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
        }

        .dataTables_wrapper .dataTables_paginate {
            justify-content: center;
            flex-wrap: wrap;
        }
    }
</style>
@endpush
@endonce
