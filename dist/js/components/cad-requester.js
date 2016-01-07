/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Components */
    TopFloor.Cds.Components.CadRequester = {};
    TopFloor.Cds.Components.CadRequester.initialize = function (parameters) {
        TopFloor.Cds.State.CadRequester = parameters;

        cds.CADRequester.setProduct(parameters.productId);
        cds.CADRequester.setContainerElementId(TopFloor.Cds.Settings.CadRequester.containerId);
        cds.CADRequester.setFormatSelectElementId(TopFloor.Cds.Settings.CadRequester.formatSelectId);
        cds.CADRequester.setDownloadButtonElementId(TopFloor.Cds.Settings.CadRequester.downloadButtonId);
        cds.CADRequester.setView2DButtonElementId(TopFloor.Cds.Settings.CadRequester.view2dButtonId);
        cds.CADRequester.setView3DButtonElementId(TopFloor.Cds.Settings.CadRequester.view3dButtonId);
        cds.CADRequester.load();
    };
})(jQuery);
