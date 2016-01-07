/**
 * Created by Ben on 7/15/2015.
 */

(function ($) {
    "use strict";

    window.defer = window.defer || [];

    /** @namespace TopFloor.Cds */
    window.TopFloor = window.TopFloor || {};

    TopFloor.Cds = TopFloor.Cds || {};

    TopFloor.Cds.Pages = TopFloor.Cds.Pages || {};
    TopFloor.Cds.Components = TopFloor.Cds.Components || {};
    TopFloor.Cds.State = TopFloor.Cds.State || {};
    TopFloor.Cds.State.initialized = false;

    TopFloor.Cds.initialize = function () {
        if (TopFloor.Cds.State.initialized) {
            return;
        }

        cds.setDomain(TopFloor.Cds.Settings.domain);
        cds.setRemoteServerBaseURL(TopFloor.Cds.Settings.baseUrl);

        TopFloor.Cds.State.initialized = true;
    };
})(jQuery);
