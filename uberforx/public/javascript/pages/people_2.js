/*! ========================================================================
 * people.js
 * Page/renders: pages-people-directory.html
 * Plugins used: shuffle
 * ======================================================================== */
$(function () {
    // Shuffle
    // ================================
    var $grid   = $("#shuffle-grid"),
        $filter = $("#shuffle-filter"),
        $sizer  = $grid.find("shuffle-sizer");
    
    // instatiate shuffle
    $grid.shuffle({
        itemSelector: ".shuffle",
        sizer: $sizer
    });

    // Filter options
    (function () {
        $filter.on("keyup change", function () {
            var val = this.value.toLowerCase();
            $grid.shuffle("shuffle", function ($el, shuffle) {

                // Only search elements in the current group
                if (shuffle.group !== "all" && $.inArray(shuffle.group, $el.data("groups")) === -1) {
                    return false;
                }

                var text = $.trim($el.find(".panel-body > h5").text()).toLowerCase();
                return text.indexOf(val) !== -1;
            });
        });
    })();
});