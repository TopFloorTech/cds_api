/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Pages */
    TopFloor.Cds.Pages.Cart = {};
    TopFloor.Cds.Pages.Cart.initialize = function (parameters) {
        cds.cart.setParentElementId(TopFloor.Cds.Settings.Cart.containerId);
        cds.cart.load();
    };
})(jQuery);
