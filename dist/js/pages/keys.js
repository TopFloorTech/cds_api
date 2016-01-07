/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Pages */
    TopFloor.Cds.Pages.Keys = {};
    TopFloor.Cds.Pages.Keys.initialize = function (parameters) {
        cds.textLabels["keyword_search_results.attribute_column_label"] = parameters.attributeLabel;
        cds.textLabels["keyword_search_results.value_column_label"] = parameters.valueLabel;
        cds.keys.containerElementId = parameters.containerId;
        cds.keys.productURLTemplate = parameters.productUrlTemplate;
        cds.keys.categoryURLTemplate = parameters.categoryUrlTemplate;
        cds.keys.queryParameter = parameters.queryParameter;
        cds.keys.load();
    };
})(jQuery);
