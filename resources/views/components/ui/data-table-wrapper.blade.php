@props([
    'tableId' => 'dataTable',
    'minWidth' => '600px',
    'showHeader' => true,
    'showFooter' => true,
    'className' => 'bg-white shadow-lg rounded-xl border border-gray-100 p-6'
])

<div class="{{ $className }}">
    <div class="datatable-scroll-container overflow-x-auto w-full -mx-2 px-2 sm:mx-0 sm:px-0">
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

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 14px;
            min-width: 36px;
            text-align: center;
        }

        .dataTables_wrapper .dataTables_info {
            text-align: center;
            font-size: 0.75rem;
        }

        /* Force horizontal scroll for tables on mobile */
        .datatable-scroll-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .dataTables_wrapper table {
            min-width: 700px;
        }

        /* DataTables scroll wrapper fix */
        .dataTables_wrapper .dataTables_scroll .dataTables_scrollBody {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
    }

    /* DataTables Responsive - Child row styling (collapsed columns on mobile) */
    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
    }

    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control::before {
        content: '+';
        display: inline-block;
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        line-height: 18px;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        color: white;
        background-color: #009B77;
        border-radius: 50%;
    }

    table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control::before,
    table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th.dtr-control::before {
        content: '-';
        background-color: #dc2626;
    }

    /* Child row details list */
    table.dataTable > tbody > tr.child ul.dtr-details {
        display: block;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    table.dataTable > tbody > tr.child ul.dtr-details li {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        gap: 12px;
    }

    table.dataTable > tbody > tr.child ul.dtr-details li:last-child {
        border-bottom: none;
    }

    table.dataTable > tbody > tr.child ul.dtr-details li .dtr-title {
        font-weight: 600;
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        white-space: nowrap;
        flex-shrink: 0;
        min-width: 100px;
    }

    table.dataTable > tbody > tr.child ul.dtr-details li .dtr-data {
        font-size: 0.875rem;
        color: #1f2937;
        text-align: right;
        word-break: break-word;
    }

    table.dataTable > tbody > tr.child td.child {
        padding: 0;
    }

    table.dataTable > tbody > tr.child:hover {
        background-color: transparent !important;
    }
</style>
@endpush
@endonce
