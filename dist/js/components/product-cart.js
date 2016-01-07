/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Components */
    TopFloor.Cds.Components.ProductCart = {};
    TopFloor.Cds.Components.ProductCart.initialize = function (parameters) {
        $("#cds-add-to-cart-button").on("click", function () {
            var pid, plabel, pdesc, pimg, purl, i, a, v, e, first,
                q = $("#cds-add-to-cart-quantity").val();

            if (parseInt(q) > 0) {
                pid = parameters.productId;
                plabel = parameters.productLabel;
                pdesc = parameters.productDescription;
                first = true;

                for (i in cds.productAttributes) {
                    a = cds.productAttributes[i];
                    v = null;

                    if (a.dataType === "range") {
                        e = document.getElementById("cds-dv-" + i);
                        if (e) {
                            v = e.value;
                            if (isNaN(parseFloat(v))) {
                                v = null;
                            }
                        }
                    } else if (a.dataType === "list") {
                        var e = document.getElementById("cds-dv-" + i);
                        if (e) {
                            v = e.options[e.selectedIndex].value;
                            if (v !== null && v.toLowerCase() === "none") {
                                v = null;
                            }
                        }
                    } else if (a.dataType === "multilist") {
                        v = "";
                        for (var j = 0; j < 100; j++) {
                            e = document.getElementById("cds-dv-" + i + "-" + j);
                            if (e) {
                                if (e.checked) {
                                    if (v.length) {
                                        v += ",";
                                    }
                                    v += e.value;
                                }
                            } else {
                                break;
                            }
                        }
                        if (!v.length) {
                            v = null;
                        }
                    } else if (a.dataType === "text") {
                        var e = document.getElementById("cds-dv-" + i);
                        if (e) {
                            v = e.value;
                            if (v && !v.length) {
                                v = null;
                            }
                        }
                    }

                    if (v) {
                        if (first) {
                            pdesc += "\r\n";
                            first = false;
                        }
                        pdesc += "\r\n" + a.label + ": " + v;
                    }
                }

                pimg = parameters.productImageUrl;
                purl = location.href;
                cds.cart.addProduct(pid, plabel, pdesc, q, purl, pimg, true, parameters.cartUrl);
            } else {
                alert('Please specify a valid quantity.');
            }
        });

        $("#cds-add-to-cart-quantity").focus(function () { this.select(); });
    };
})(jQuery);
