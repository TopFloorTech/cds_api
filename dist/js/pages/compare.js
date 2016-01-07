/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Pages */
    TopFloor.Cds.Pages.Compare = {};
    TopFloor.Cds.Pages.Compare.initialize = function (parameters) {
        cds.productCompareTable.setProductURLTemplate(TopFloor.Cds.Settings.Compare.productUrlTemplate);
        cds.productCompareTable.setParentElementId(TopFloor.Cds.Settings.Compare.containerId);
        cds.productCompareTable.load();
    };
})(jQuery);
