/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Components */
    TopFloor.Cds.Components.ProductImages = {};
    TopFloor.Cds.Components.ProductImages.expandedSchematic = null;

    TopFloor.Cds.Components.ProductImages.expandImage = function (src) {
        if (TopFloor.Cds.Components.ProductImages.expandedSchematic && TopFloor.Cds.Components.ProductImages.expandedSchematic === src) {
            $('#cds-product-additional-images-expanded-img').attr('src', '');
            $('#cds-product-additional-images-expanded').hide();
            TopFloor.Cds.Components.ProductImages.expandedSchematic = null;
        } else {
            $('#cds-product-additional-images-expanded-img').attr('src', src);
            $('#cds-product-additional-images-expanded').show();
            TopFloor.Cds.Components.ProductImages.expandedSchematic = src;
        }

        return false;
    };
})(jQuery);
