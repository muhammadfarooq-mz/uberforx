/*! ========================================================================
 * default.js
 * Page/renders: table-default.html
 * Plugins used: sparkline
 * ======================================================================== */
$(function () {
    // Sparkline
    // ================================
    $(".sparklines").sparkline("html", {
        enableTagOptions: true
    });
});