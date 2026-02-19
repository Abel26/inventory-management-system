@once
@push('styles')
<!-- Global SweetAlert aria-hidden fix for all forms -->
<style>
/* Fix for aria-hidden conflicts across ALL buttons and interactive elements */
.flex.h-screen[aria-hidden="true"] button,
.flex.h-screen[aria-hidden="true"] button[type="submit"],
.flex.h-screen[aria-hidden="true"] #submitMaterialBtn,
.flex.h-screen[aria-hidden="true"] #submitToolBtn,
.flex.h-screen[aria-hidden="true"] #submitModelBtn,
.flex.h-screen[aria-hidden="true"] #submitUserBtn,
.flex.h-screen[aria-hidden="true"] #submitRoleBtn,
.flex.h-screen[aria-hidden="true"] #submitReportBtn,
.flex.h-screen[aria-hidden="true"] #submitProfileBtn,
.flex.h-screen[aria-hidden="true"] #submitSatuanBtn,
.flex.h-screen[aria-hidden="true"] #submitGedungBtn,
.flex.h-screen[aria-hidden="true"] input[type="submit"],
.flex.h-screen[aria-hidden="true"] input[type="button"],
.flex.h-screen[aria-hidden="true"] a[role="button"],
.flex.h-screen[aria-hidden="true"] [role="button"],
.flex.h-screen[aria-hidden="true"] .btn,
.flex.h-screen[aria-hidden="true"] [onclick] {
    pointer-events: auto !important;
    visibility: visible !important;
    opacity: 1 !important;
    z-index: auto !important;
}

/* Ensure SweetAlert doesn't hide our buttons */
body.swal2-shown:not(.swal2-toast-shown) .flex.h-screen {
    aria-hidden: unset !important;
}

/* Additional fallback for any form submit button */
body.swal2-shown:not(.swal2-toast-shown) form button[type="submit"],
body.swal2-shown:not(.swal2-toast-shown) form input[type="submit"],
body.swal2-shown:not(.swal2-toast-shown) form input[type="button"],
body.swal2-shown:not(.swal2-toast-shown) form a[role="button"] {
    pointer-events: auto !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Global override for any button inside aria-hidden container */
[aria-hidden="true"] button,
[aria-hidden="true"] input[type="submit"],
[aria-hidden="true"] input[type="button"],
[aria-hidden="true"] a[role="button"],
[aria-hidden="true"] [role="button"],
[aria-hidden="true"] .btn {
    pointer-events: auto !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Special override for focused elements */
:focus[aria-hidden="true"],
:focus[aria-hidden="true"] *,
button:focus[aria-hidden="true"],
input:focus[aria-hidden="true"],
a:focus[aria-hidden="true"] {
    pointer-events: auto !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>
@endpush

@push('scripts')
<script>
// Global SweetAlert aria-hidden conflict prevention
(function() {
    'use strict';
    
    console.log('GLOBAL SWEETALERT FIX: Initializing...');
    
    // Override SweetAlert's default behavior globally
    if (typeof Swal !== 'undefined') {
        const originalFire = Swal.fire;
        
        Swal.fire = function(options) {
            // Add default didOpen and didClose handlers
            const enhancedOptions = Object.assign({}, options, {
                didOpen: function() {
                    // Remove aria-hidden from main container when SweetAlert opens
                    $('.flex.h-screen').removeAttr('aria-hidden');
                    console.log('GLOBAL SWEETALERT FIX: Removed aria-hidden from main container');
                    
                    // Call original didOpen if provided
                    if (options.didOpen && typeof options.didOpen === 'function') {
                        options.didOpen.call(this);
                    }
                },
                didClose: function() {
                    console.log('GLOBAL SWEETALERT FIX: SweetAlert closed');
                    
                    // Call original didClose if provided
                    if (options.didClose && typeof options.didClose === 'function') {
                        options.didClose.call(this);
                    }
                }
            });
            
            return originalFire.call(this, enhancedOptions);
        };
        
        console.log('GLOBAL SWEETALERT FIX: SweetAlert overridden successfully');
    }
    
    // Prevent any button from being trapped by aria-hidden
    document.addEventListener('click', function(e) {
        const button = e.target.closest('button');
        if (button) {
            // Remove focus from any button before it triggers SweetAlert
            setTimeout(() => {
                if (document.querySelector('.swal2-container')) {
                    button.blur();
                }
            }, 10);
        }
    }, true);
    
    // Monitor for any aria-hidden being added dynamically
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'aria-hidden') {
                const target = mutation.target;
                if (target.classList.contains('flex') && target.classList.contains('h-screen')) {
                    // Check if any button is visible and has focus
                    const activeElement = document.activeElement;
                    if (activeElement && activeElement.tagName === 'BUTTON') {
                        console.log('GLOBAL SWEETALERT FIX: Preventing aria-hidden on focused button');
                        target.removeAttribute('aria-hidden');
                    }
                }
            }
        });
    });
    
    // Start observing the main container
    const mainContainer = document.querySelector('.flex.h-screen');
    if (mainContainer) {
        observer.observe(mainContainer, {
            attributes: true,
            attributeFilter: ['aria-hidden']
        });
    }
    
    // Global fix for any dynamically added aria-hidden
    setInterval(function() {
        const $container = $('.flex.h-screen');
        if ($container.length && $container.attr('aria-hidden') === 'true') {
            // Check if any submit button is visible
            const $visibleSubmit = $('button[type="submit"]:visible, #submitMaterialBtn:visible, #submitToolBtn:visible, #submitModelBtn:visible');
            
            if ($visibleSubmit.length > 0) {
                $container.removeAttr('aria-hidden');
                console.log('GLOBAL SWEETALERT FIX: Auto-removed aria-hidden conflict');
            }
        }
    }, 1000);
    
    // Add blur handler to all submit buttons before SweetAlert
    $(document).on('click', 'button[type="submit"], #submitMaterialBtn, #submitToolBtn, #submitModelBtn, #submitUserBtn, #submitRoleBtn, #submitReportBtn, #submitSatuanBtn, #submitGedungBtn', function(e) {
        // Check if this click will trigger SweetAlert
        const $form = $(this).closest('form');
        const hasSweetAlert = $form.length && ($form.text().includes('Swal.fire') || $form.html().includes('Swal.fire'));
        
        if (hasSweetAlert || $(this).attr('onclick') || $(this).data('swal')) {
            console.log('GLOBAL SWEETALERT FIX: Blurring submit button before SweetAlert');
            $(this).blur();
        }
    });
    
    console.log('GLOBAL SWEETALERT FIX: Initialization complete');
})();
</script>
@endpush
@endonce