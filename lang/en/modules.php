<?php

return [
    // ===========================
    // COMMON / SHARED STRINGS
    // ===========================
    'common' => [
        // Buttons
        'add_data' => 'Add Data',
        'export' => 'Export',
        'export_excel' => 'Export to Excel',
        'export_pdf' => 'Export to PDF',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'close' => 'Close',
        'back' => 'Back',
        'submit' => 'Submit',
        'saving' => 'Saving...',
        'close_modal' => 'Close modal',
        'print_qr' => 'Print QR Code',
        'scan_qr_detail' => 'Scan this QR code to view details',
        'select_material' => 'Select Material',

        // Labels
        'code' => 'Code',
        'name' => 'Name',
        'type' => 'Type',
        'category' => 'Category',
        'brand' => 'Brand',
        'quantity' => 'Quantity',
        'unit' => 'Unit',
        'location' => 'Location',
        'condition' => 'Condition',
        'description' => 'Description',
        'supplier' => 'Supplier',
        'status' => 'Status',
        'actions' => 'Actions',
        'year' => 'Year',
        'stock' => 'Stock',
        'photo' => 'Photo',
        'notes' => 'Notes',
        'email' => 'Email',
        'password' => 'Password',
        'role' => 'Role',
        'priority' => 'Priority',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'no' => 'No',

        // Placeholders
        'optional' => 'Optional',
        'example' => 'Example',
        'select' => 'Select',
        'search' => 'Search...',

        // Select Options - Conditions
        'select_condition' => 'Select Condition',
        'condition_good' => 'Good',
        'condition_repair' => 'Repair',
        'condition_damaged' => 'Damaged',
        'condition_disposed' => 'Disposed',

        // Other
        'other' => 'Other',
        'no_data' => 'No data',
        'required' => 'Required',
    ],

    // ===========================
    // DATATABLES LANGUAGE
    // ===========================
    'datatable' => [
        'search' => 'Search:',
        'length_menu' => 'Show _MENU_ entries',
        'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
        'info_empty' => 'No entries available',
        'info_filtered' => '(filtered from _MAX_ total entries)',
        'zero_records' => 'No matching records found',
        'empty_table' => 'No data available in table',
        'first' => 'First',
        'last' => 'Last',
        'next' => 'Next',
        'previous' => 'Previous',
        'sort_ascending' => ': activate to sort column ascending',
        'sort_descending' => ': activate to sort column descending',
    ],

    // ===========================
    // SWEETALERT MESSAGES
    // ===========================
    'swal' => [
        'confirm_title' => 'Are you sure?',
        'delete_warning' => 'Deleted data cannot be recovered',
        'update_warning' => 'Data will be updated. Make sure the data is correct.',
        'create_warning' => 'Data will be added. Make sure the data is correct.',
        'yes_save' => 'Yes, Save',
        'yes_delete' => 'Yes, Delete',
        'cancel' => 'Cancel',
        'success' => 'Success',
        'error_title' => 'Error',
        'error' => 'Error',
        'data_saved' => 'Data saved successfully',
        'data_updated' => 'Data updated successfully',
        'data_deleted' => 'Data deleted successfully',
        'fetch_error' => 'Failed to fetch data',
        'delete_error' => 'Failed to delete data',
        'generic_error' => 'An error occurred. Please try again.',
        'validation_error' => 'Validation error. Please check your input.',
        'permission_error' => 'You do not have permission to perform this action.',
        'not_found' => 'Data not found.',
        'server_error' => 'A server error occurred. Please try again.',
        'qr_error' => 'Failed to generate QR Code',
        'qr_load_error' => 'Failed to load QR Code',
        'qr_code_for' => 'QR Code for:',
        // Report-specific
        'submit_report' => 'Submit Report?',
        'submit_report_text' => 'Make sure the data you entered is correct.',
        'yes_submit' => 'Yes, Submit!',
        'ok' => 'OK',
    ],

    // ===========================
    // ASSET MATERIALS
    // ===========================
    'asset_materials' => [
        'title' => 'Asset Materials',
        'subtitle' => 'Manage material inventory and supplies',
        'search_placeholder' => 'Search materials...',
        'empty_state' => 'No Asset Material data found.',

        // Stats
        'total_materials' => 'Total Materials',
        'low_stock' => 'Low Stock',
        'out_of_stock' => 'Out of Stock',
        'total_value' => 'Total Value',

        // Table Headers
        'asset_name' => 'Asset Name',

        // Modal
        'add_title' => 'Add Material',
        'add_subtitle' => 'Fill in new material information',
        'edit_title' => 'Edit Material',
        'edit_subtitle' => 'Edit material information',

        // Form Labels
        'material_code' => 'Material Code',
        'material_name' => 'Material Name',
        'min_stock' => 'Min. Stock',
        'unit_price' => 'Unit Price',
        'entry_date' => 'Entry Date',
        'expiry_date' => 'Expiry Date',
        'code_placeholder' => 'Optional, will be auto-generated',

        // Select Options
        'select_type' => 'Select Type',
        'select_unit' => 'Select Unit',
        'type_raw_material' => 'Raw Material',
        'type_component' => 'Component',
        'type_accessory' => 'Accessory',
        'type_other' => 'Other',

        // Error Messages
        'fetch_error' => 'Failed to fetch material data',
        
        // Confirmation Messages
        'confirm_create' => 'Are you sure you want to add a new material?',
        'confirm_close' => 'There are unsaved changes. Are you sure you want to close?',
        'validation_failed' => 'Validation Failed',
        'name_required' => 'Material name is required',
        'type_required' => 'Material type is required',
        'quantity_invalid' => 'Quantity is required and cannot be negative',
        'unit_required' => 'Unit is required',
        'min_threshold_invalid' => 'Minimum stock is required and cannot be negative',
        'entry_date_required' => 'Entry date is required',
        'yes_create' => 'Yes, Add',
        'yes_close' => 'Yes, Close',
    ],

    // ===========================
    // ASSET TOOLS
    // ===========================
    'asset_tools' => [
        'title' => 'Asset Tools',
        'subtitle' => 'Manage tool and equipment inventory',
        'search_placeholder' => 'Search tools...',
        'empty_state' => 'No Asset Tool data found.',

        // Stats
        'total_tools' => 'Total Tools',
        'good_condition' => 'Good Condition',
        'repair' => 'Repair',
        'damaged' => 'Damaged',

        // Modal
        'add_title' => 'Add Tool',
        'add_subtitle' => 'Fill in new tool information',
        'edit_title' => 'Edit Tool',
        'edit_subtitle' => 'Edit tool information',

        // Form Labels
        'tool_code' => 'Tool Code',
        'tool_name' => 'Tool Name',
        'purchase_date' => 'Purchase Date',
        'purchase_price' => 'Purchase Price',

        // Placeholders
        'code_placeholder' => 'Example: TL-001',
        'brand_placeholder' => 'Example: Mitsubishi, Bosch',
        'type_placeholder' => 'Example: MX-200',
        'price_placeholder' => 'Example: 1500000',
        'quantity_placeholder' => 'Example: 1',

        // Select Options - Categories
        'select_category' => 'Select Category',
        'category_hand_tools' => 'Hand Tools',
        'category_power_tools' => 'Power Tools',
        'category_measuring' => 'Measuring Tools',
        'category_other' => 'Other',

        // Error Messages
        'fetch_error' => 'Failed to fetch tool data',
        'update_confirm' => 'Tool data will be updated. Make sure the data is correct.',
        'create_confirm' => 'Tool data will be added. Make sure the data is correct.',
        'delete_permission_error' => 'You do not have permission to delete this data',
    ],

    // ===========================
    // ASSET MODELS
    // ===========================
    'asset_models' => [
        'title' => 'Asset Models',
        'subtitle' => 'Manage model and mold inventory',
        'search_placeholder' => 'Search models...',
        'empty_state' => 'No Asset Model data found.',

        // Stats
        'total_models' => 'Total Models',
        'good_condition' => 'Good Condition',
        'repair' => 'Repair',
        'damaged' => 'Damaged',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'total_assets' => 'Total Assets',

        // Modal
        'add_title' => 'Add Model',
        'add_subtitle' => 'Fill in new model information',
        'edit_title' => 'Edit Model',
        'edit_subtitle' => 'Edit model information',

        // Form Labels
        'model_code' => 'Model Code',
        'model_name' => 'Model Name',
        'purchase_date' => 'Purchase Date',
        'purchase_price' => 'Purchase Price',
        'manufacture_date' => 'Manufacture Date',
        'material' => 'Material',
        'type_placeholder' => 'Example: Injection, CNC',
        'save_error' => 'An error occurred while saving data',

        // Table Headers
        'model_number' => 'Model Number',

        // Select Options - Categories
        'select_category' => 'Select Category',
        'category_mold' => 'Mold',
        'category_die' => 'Die',
        'category_jig' => 'Jig',
        'category_fixture' => 'Fixture',
        'category_other' => 'Other',

        // Error Messages
        'fetch_error' => 'Failed to fetch model data',
    ],

    // ===========================
    // GEDUNGS (BUILDINGS)
    // ===========================
    'gedungs' => [
        'title' => 'Building Data',
        'subtitle' => 'Manage building and location data',
        'search_placeholder' => 'Search buildings...',
        'empty_state' => 'No Building data found.',

        // Stats
        'total_buildings' => 'Total Buildings',
        'total_floors' => 'Total Floors',
        'total_rooms' => 'Total Rooms',
        'active_locations' => 'Active Locations',

        // Table Headers
        'building_name' => 'Building Name',
        'address' => 'Address',
        'floor_count' => 'Floors',
        'room_count' => 'Rooms',
        'total_models' => 'Total Models',
        'total_materials' => 'Total Materials',
        'total_tools' => 'Total Tools',
        'total_assets' => 'Total Assets',
        'total_assets_in_building' => 'Total Assets in Building',

        // Modal
        'add_title' => 'Add Building',
        'add_subtitle' => 'Fill in new building information',
        'edit_title' => 'Edit Building',
        'edit_subtitle' => 'Edit building information',

        // Form Labels
        'building_code' => 'Building Code',
        'code_placeholder' => 'Example: GD-001',
        'name_placeholder' => 'Example: Production Building A',
        'save_building' => 'Save Building',
        'update_subtitle' => 'Update building information',

        // Info Box
        'info_title' => 'Building Information',
        'info_description' => 'Buildings are used as references for asset locations. Make sure to fill in the data correctly.',

        // Error Messages
        'fetch_error' => 'Failed to fetch building data',
    ],

    // ===========================
    // SATUANS (UNITS)
    // ===========================
    'satuans' => [
        'title' => 'Unit Data',
        'subtitle' => 'Manage measurement unit data',
        'search_placeholder' => 'Search units...',
        'empty_state' => 'No Unit data found.',

        // Stats
        'total_units' => 'Total Units',

        // Table Headers
        'unit_name' => 'Unit Name',
        'abbreviation' => 'Abbreviation',

        // Modal
        'add_title' => 'Add Unit',
        'add_subtitle' => 'Fill in new unit information',
        'edit_title' => 'Edit Unit',
        'edit_subtitle' => 'Edit unit information',

        // Form Labels
        'unit_code' => 'Unit Code',
        'unit_symbol' => 'Symbol',
        'name_placeholder' => 'Example: Kilogram, Liter',
        'code_placeholder' => 'Example: kg, ltr',
        'update_subtitle' => 'Edit unit information',

        // Error Messages
        'fetch_error' => 'Failed to fetch unit data',
    ],

    // ===========================
    // USERS
    // ===========================
    'users' => [
        'title' => 'User Management',
        'subtitle' => 'Manage user accounts and access',
        'search_placeholder' => 'Search users...',
        'empty_state' => 'No User data found.',

        // Stats
        'total_users' => 'Total Users',
        'active_users' => 'Active Users',
        'admin_users' => 'Admin Users',
        'regular_users' => 'Regular Users',

        // Table Headers
        'user_name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
        'status' => 'Status',

        // Modal
        'add_title' => 'Add User',
        'add_subtitle' => 'Fill in new user information',
        'edit_title' => 'Edit User',
        'edit_subtitle' => 'Edit user information',

        // Form Labels
        'full_name' => 'Full Name',
        'email_address' => 'Email Address',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'select_role' => 'Select Role',
        'password_info' => 'Leave blank to keep current password',

        // Status
        'active' => 'Active',
        'inactive' => 'Inactive',

        // Error Messages
        'user_info' => 'User Info',
        'username' => 'Username',
        'phone' => 'Phone',

        'is_active' => 'Active User',
        'active_help' => 'Enable to grant login access',
        'name_placeholder' => 'Enter full name',
        'username_placeholder' => 'Enter username',
        'phone_placeholder' => 'Enter phone number',
        'password_placeholder' => 'Enter new password',
        'password_conf_placeholder' => 'Confirm new password',
        'empty_state_desc' => 'Start by adding the first user',
        'fetch_error' => 'Failed to fetch user data',
    ],

    // ===========================
    // ROLES
    // ===========================
    'roles' => [
        'title' => 'Role Management',
        'subtitle' => 'Manage roles and permissions',
        'search_placeholder' => 'Search roles...',
        'empty_state' => 'No Role data found.',

        // Stats
        'total_roles' => 'Total Roles',

        // Table Headers
        'role_name' => 'Role Name',
        'permissions' => 'Permissions',
        'user_count' => 'Users',
        'guard_name' => 'Guard',

        // Modal
        'add_title' => 'Add Role',
        'add_subtitle' => 'Fill in new role information',
        'edit_title' => 'Edit Role',
        'edit_subtitle' => 'Edit role information',

        // Error Messages
        'permissions_count' => 'Permission Count',
        'fetch_error' => 'Failed to fetch role data',
    ],

    // ===========================
    // REPORTS (LAPORAN MASALAH)
    // ===========================
    'reports' => [
        'title' => 'Issue Reports',
        'subtitle' => 'Manage and track asset issue reports',
        'search_placeholder' => 'Search reports...',
        'empty_state' => 'No report data found.',

        // Stats
        'total_reports' => 'Total Reports',
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',

        // Table Headers
        'report_code' => 'Report Code',
        'asset_name' => 'Asset Name',
        'issue_type' => 'Issue Type',
        'priority' => 'Priority',
        'reporter' => 'Reporter',
        'date' => 'Date',

        // Filter Buttons
        'all' => 'All',
        'status_pending' => 'Pending',
        'status_in_progress' => 'In Progress',
        'status_resolved' => 'Resolved',
        'status_rejected' => 'Rejected',

        // Create Page
        'create_title' => 'Create Report',
        'create_subtitle' => 'Report an issue with an asset',
        'no_asset_selected' => 'No asset selected',
        'scan_qr_first' => 'Please scan the asset QR code first to create a report.',
        'scan_qr_code' => 'Scan QR Code',

        // Create Form - Issue Types
        'issue_damage' => 'Damage',
        'issue_maintenance' => 'Maintenance',
        'issue_lost' => 'Lost',
        'issue_stock_discrepancy' => 'Stock Discrepancy',

        // Create Form - Priority
        'priority_low' => 'Low',
        'priority_medium' => 'Medium',
        'priority_high' => 'High',
        'priority_critical' => 'Critical',

        // Create Form - Fields
        'description_placeholder' => 'Describe the issue...',
        'photo_evidence' => 'Photo Evidence (Optional)',
        'photo_max_size' => 'Maximum 5MB. Format: JPG, PNG, GIF.',
        'upload' => 'Upload',
        'submit_report' => 'Submit Report',

        // Show Page
        'detail_title' => 'Report Detail',
        'created_at' => 'Created on',
        'asset_details' => 'Asset Details',
        'view_asset' => 'View Asset',
        'asset_not_found' => 'Asset not found or has been deleted.',
        'issue_description' => 'Issue Description',
        'reported_by' => 'Reported By',
        'update_status' => 'Update Status',
        'admin_note' => 'Admin Note',
        'admin_note_placeholder' => 'Add a note about the resolution...',
        'status_history' => 'Status History',
        'report_created' => 'Report Created',
        'status_changed_to' => 'Status changed to',
        'resolved_by' => 'Resolved by',
        'note_label' => 'Note:',
        'back' => 'Back',

        // Scan
        'scan_title' => 'Scan Asset QR Code',
        'scan_description' => 'Use your device camera to scan the asset QR code or enter the code manually',
        'camera_mode' => 'Live Camera Mode (HTTPS) - Real-time camera available',
        'camera_mode_desc' => 'Live Camera Ready',
        'camera_mode_instruction' => 'Click "Start Live Scan" to begin',
        'start_scan_live' => 'Start Live Scan',
        'standard_mode' => 'Standard Mode (HTTP) - Scan via photo',
        'standard_mode_desc' => 'Scan via Photo',
        'standard_mode_instruction' => 'Click "Start Scan" to take photo',
        'take_photo' => 'Take QR Photo',
        'scanner_title' => 'QR Scanner',
        'preparing_scanner' => 'Preparing scanner...',
        'wait_moment' => 'Please wait a moment',
        'start_scan' => 'Start Scan',
        'stop_scan' => 'Stop Scan',
        'manual_input_title' => 'Manual Input',
        'manual_input_label' => 'QR Code / Asset Code',
        'manual_input_placeholder' => 'Example: A001 or MOD-001',
        'search_asset' => 'Search Asset',
        'supported_assets' => 'Supported Assets',
        'tips_scan' => 'Scan Tips',
        'tip_light' => 'Ensure sufficient lighting',
        'tip_steady' => 'Hold device steadily',
        'tip_distance' => 'Ideal distance: 10-20 cm from QR code',
        'tip_manual' => 'Use manual input if scan fails',
        'processing_image' => 'Processing image...',
        'reading_qr' => 'Reading QR code from photo',
        'qr_success' => 'QR Code successfully read: ',
        'camera_error' => 'Failed to open camera. Please try again.',
        'camera_permission_denied' => 'Camera permission denied. Please allow camera access in your browser.',
        'camera_not_found' => 'Camera not found. Ensure your device has a camera.',
        'camera_in_use' => 'Camera is being used by another application.',
        'camera_constraint' => 'Camera does not meet required constraints.',
        'scan_image_error' => 'Unable to read QR code from image.',
        'no_qr_found' => 'QR code not found in image. Ensure QR code is clearly visible.',
        'decode_error' => 'Failed to process image. Please try another image.',

        // Inventory Report
        'inventory_title' => 'Inventory Report',
        'inventory_subtitle' => 'View inventory analytics and statistics',
        'coming_soon' => 'This feature is coming soon.',
        'coming_soon_desc' => 'You will be able to:',
        'feature_stock_levels' => 'View stock levels by category',
        'feature_stock_movements' => 'Track stock movements over time',
        'feature_valuation' => 'Generate inventory valuation reports',
        'feature_export' => 'Export to Excel/PDF',

        // Transactions Report
        'transactions_title' => 'Transactions',
        'transactions_subtitle' => 'View all inventory transactions',
        'transactions_report_title' => 'Transactions Report',
        'feature_all_movements' => 'View all stock movements',
        'feature_filter_date' => 'Filter by date range',
        'feature_filter_product' => 'Filter by product',
        'feature_filter_type' => 'Filter by transaction type',
    ],
];
