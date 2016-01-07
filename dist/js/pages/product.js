/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Pages */
    TopFloor.Cds.Pages.Product = {};
    TopFloor.Cds.Pages.Product.initialize = function (parameters) {
        cds.catalogCommand = TopFloor.Cds.Settings.Product.catalogCommand;
        cds.productID = parameters.productId;
        cds.productAttributes = parameters.productAttributes;

        if (TopFloor.Cds.Settings.Product.listPrice > 0) {
            cds.listPrice = TopFloor.Cds.Settings.Product.listPrice;
        }

        if (TopFloor.Cds.Settings.Product.quantityDiscountSchedule) {
            cds.quantityDiscountSchedule = TopFloor.Cds.Settings.Product.quantityDiscountSchedule;
        }

        $(window).load(function () {
            if (typeof cdsHandleWindowOnLoad === 'function') {
                cdsHandleWindowOnLoad();
            }
        });
    };
})(jQuery);
