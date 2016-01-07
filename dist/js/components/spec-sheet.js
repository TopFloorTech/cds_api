/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Components */
    TopFloor.Cds.Components.SpecSheet = {};
    TopFloor.Cds.Components.SpecSheet.initialize = function (parameters) {
        cds.specSheet.unit = parameters.unitSystem;
        cds.specSheet.params = {
            id: parameters.productId,
            cid: parameters.categoryId
        };

        cds.specSheet.load('cds-product-spec-sheet-submit');
    };
})(jQuery);
