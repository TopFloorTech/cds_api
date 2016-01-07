/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Components */
    TopFloor.Cds.Components.UnitToggle = {};
    TopFloor.Cds.Components.UnitToggle.initialize = function (parameters) {
        cds.addUnitSystemToggle(parameters.containerId, parameters.unitSystem);
    };
})(jQuery);
