/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Pages */
    TopFloor.Cds.Pages.Search = {};
    TopFloor.Cds.Pages.Search.initialize = function (parameters) {
        TopFloor.Cds.State.Search = parameters;

        $(window).on('load', function () {
            var widest = -1;
            var cdsBrowseList = $('.cds-browse-list');

            cdsBrowseList.find('img').each(function (index, element) {
                var width = $(element).width();

                if (width > widest) {
                    widest = width;
                }
            });

            if (widest > -1) {
                cds.makeSameWidth(cdsBrowseList.children(), null, widest);
                cds.makeSameHeight(cdsBrowseList.children());
            }
        });

        cds.facetedSearch.searchURLTemplate = TopFloor.Cds.Settings.Search.searchUrlTemplate;
        cds.facetedSearch.productURLTemplate = TopFloor.Cds.Settings.Search.productUrlTemplate;
        cds.facetedSearch.categoryId = TopFloor.Cds.State.Search.categoryId;
        cds.facetedSearch.displayPowerGrid = TopFloor.Cds.State.Search.displayPowerGrid;
        cds.facetedSearch.renderProductsListType = TopFloor.Cds.State.Search.renderProductsListType;
        cds.facetedSearch.showUnitToggle = TopFloor.Cds.State.Search.showUnitToggle;
        cds.facetedSearch.showKeywordSearch = parameters.enableKeywordSearch;
        cds.facetedSearch.appendUnitToProductURL = TopFloor.Cds.State.Search.appendUnitToProductURL;
        cds.facetedSearch.loadProducts = TopFloor.Cds.State.Search.loadProducts;

        cds.facetedSearch.init();

        cds.facetedSearch.compareCart = new cds.ProductCompareCart();
        cds.facetedSearch.compareCart.setComparePageURL(TopFloor.Cds.Settings.Search.comparePageUrl);
        cds.facetedSearch.compareCart.setMaxProducts(6);
    };
})(jQuery);
