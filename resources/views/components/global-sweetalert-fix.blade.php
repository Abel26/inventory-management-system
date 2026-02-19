@once
@push('styles')
<!-- Global SweetAlert z-index and interaction fix -->
<style>
/*
  When SweetAlert2 opens it sets aria-hidden="true" on sibling containers.
  Ensure pointer-events still work inside those containers (Chrome/Firefox
  block focus but NOT clicks for aria-hidden; Safari may differ).
*/
[aria-hidden="true"] button,
[aria-hidden="true"] input[type="submit"],
[aria-hidden="true"] input[type="button"],
[aria-hidden="true"] [role="button"] {
    pointer-events: auto !important;
}
</style>
@endpush

@push('scripts')
<script>
/**
 * Global SweetAlert2 helper
 * - Blurs the active submit button before any Swal fires to avoid
 *   focus-trap / aria-hidden interaction quirks in SweetAlert v11.
 * - Applies to both HTTP and HTTPS environments.
 */
(function () {
    if (typeof $ === 'undefined') return; // jQuery not yet loaded

    $(document).on('click', [
        'button[type="submit"]',
        '#submitMaterialBtn',
        '#submitToolBtn',
        '#submitModelBtn',
        '#submitUserBtn',
        '#submitRoleBtn',
        '#submitReportBtn',
        '#submitSatuanBtn',
        '#submitGedungBtn',
    ].join(','), function () {
        $(this).blur();
    });
})();
</script>
@endpush
@endonce