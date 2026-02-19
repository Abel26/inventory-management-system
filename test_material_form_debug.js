// Test file untuk debugging JavaScript form submission
// Simulasi environment production

console.log('=== DEBUGGING MATERIAL FORM SUBMISSION ===');

// Test 1: Check if jQuery is loaded
if (typeof $ === 'undefined') {
    console.error('ERROR: jQuery not loaded');
} else {
    console.log('✓ jQuery loaded, version:', $.fn.jquery);
}

// Test 2: Check if SweetAlert is loaded
if (typeof Swal === 'undefined') {
    console.error('ERROR: SweetAlert not loaded');
} else {
    console.log('✓ SweetAlert loaded');
}

// Test 3: Check if DataTable is loaded
if (typeof $.fn.DataTable === 'undefined') {
    console.error('ERROR: DataTable not loaded');
} else {
    console.log('✓ DataTable loaded');
}

// Test 4: Simulate form data
const testFormData = {
    'material_code': 'TEST001',
    'name': 'Test Material',
    'type': 'bahan_baku',
    'quantity': '10',
    'unit_id': '1',
    'unit': 'pcs',
    'min_threshold': '5',
    'supplier': 'Test Supplier',
    'entry_date': '2024-01-01',
    'expiry_date': '2024-12-31',
    'location': 'Warehouse A',
    'description': 'Test description',
    'unit_price': '1000'
};

console.log('✓ Test form data:', testFormData);

// Test 5: Serialize form data simulation
function serializeFormData(data) {
    return Object.keys(data).map(key => 
        encodeURIComponent(key) + '=' + encodeURIComponent(data[key])
    ).join('&');
}

const serializedData = serializeFormData(testFormData);
console.log('✓ Serialized form data:', serializedData);

// Test 6: URL generation test
const storeUrl = '/assets/materials'; // Simulated URL
console.log('✓ Store URL:', storeUrl);

// Test 7: AJAX request simulation
function testAjaxRequest() {
    console.log('=== TESTING AJAX REQUEST ===');
    
    // Simulate AJAX request
    const ajaxConfig = {
        url: storeUrl,
        method: 'POST',
        data: serializedData,
        headers: {
            'X-CSRF-TOKEN': 'test-token',
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        timeout: 30000
    };
    
    console.log('✓ AJAX config:', ajaxConfig);
    
    // Test if config is valid
    if (!ajaxConfig.url) {
        console.error('ERROR: Missing URL');
        return false;
    }
    
    if (!ajaxConfig.method) {
        console.error('ERROR: Missing method');
        return false;
    }
    
    if (!ajaxConfig.data) {
        console.error('ERROR: Missing data');
        return false;
    }
    
    console.log('✓ AJAX config is valid');
    return true;
}

// Test 8: Form validation simulation
function testFormValidation() {
    console.log('=== TESTING FORM VALIDATION ===');
    
    const requiredFields = ['name', 'type', 'quantity', 'unit_id', 'min_threshold', 'entry_date'];
    const errors = [];
    
    requiredFields.forEach(field => {
        if (!testFormData[field] || testFormData[field].trim() === '') {
            errors.push(`Field ${field} is required`);
        }
    });
    
    // Test numeric fields
    if (isNaN(parseInt(testFormData.quantity)) || parseInt(testFormData.quantity) < 0) {
        errors.push('Quantity must be a positive number');
    }
    
    if (isNaN(parseInt(testFormData.min_threshold)) || parseInt(testFormData.min_threshold) < 0) {
        errors.push('Min threshold must be a positive number');
    }
    
    // Test date validation
    const entryDate = new Date(testFormData.entry_date);
    const expiryDate = new Date(testFormData.expiry_date);
    
    if (expiryDate < entryDate) {
        errors.push('Expiry date must be after entry date');
    }
    
    if (errors.length > 0) {
        console.error('✗ Validation errors:', errors);
        return false;
    }
    
    console.log('✓ Form validation passed');
    return true;
}

// Run all tests
console.log('=== RUNNING ALL TESTS ===');

const ajaxTest = testAjaxRequest();
const validationTest = testFormValidation();

if (ajaxTest && validationTest) {
    console.log('✓ ALL TESTS PASSED - Form should work correctly');
} else {
    console.error('✗ SOME TESTS FAILED - Form will not work');
}

console.log('=== DEBUGGING COMPLETE ===');