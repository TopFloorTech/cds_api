/**
 * Created by BMcClure on 12/29/2015.
 */

(function ($) {
    "use strict";

    /** @namespace TopFloor.Cds.Components */
    TopFloor.Cds.Components.ConfigurableAttributes = {};
    TopFloor.Cds.Components.ConfigurableAttributes.initialize = function (parameters) {
        TopFloor.Cds.State.ConfigurableAttributes = parameters;

        $(window).load(function () {
            TopFloor.Cds.Components.ConfigurableAttributes.setCustomProductLabel(parameters);

            // run once to make sure we deal with initial dynamic attributes
            if (typeof cdsHandleChangeDynamicAttribute === "function") {
                cdsHandleChangeDynamicAttribute();
            }
        });
    };

    cds.handleChangeDynamicAttribute = function (id) {
        var i, e, v, min, max, a = cds.productAttributes[id];
        v = null;
        if (a) {
            if (a.dataType === 'range') {
                min = a.value && parseFloat(a.value[0]);
                max = a.value && parseFloat(a.value[1]);
                if (min && max) {
                    e = document.getElementById('cds-dv-' + id);
                    if (e) {
                        v = parseFloat(e.value);
                        if (isNaN(v) || v < min || v > max) {
                            alert('Please enter a value between ' + min + ' and ' + max + '.');
                            // can't use a.value.default on IE9 compat view for some reason
                            v = a['value']['default'] || '';
                            e.value = v;
                            e.focus();
                        } else {
                            v = (Math.round(v * Math.pow(10, a.precision)) /
                            Math.pow(10, a.precision)).toFixed(a.precision);
                            e.value = v;
                        }
                        if (a.cadParameterName) {
                            cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                        }
                        cds.cart.addCustomAttribute(a.label, v);
                    }
                }
            } else if (a.dataType === 'list') {
                e = document.getElementById('cds-dv-' + id);
                if (e) {
                    v = e.options[e.selectedIndex].value;
                    if (v) {
                        if (a.cadParameterName) {
                            cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                        }
                        cds.cart.addCustomAttribute(a.label, v);
                    }
                }
            } else if (a.dataType === 'multilist') {
                v = '';
                for (i = 0; i < 100; i++) {
                    e = document.getElementById('cds-dv-' + id + '-' + i);
                    if (e) {
                        if (e.checked) {
                            if (v.length) {
                                v += ',';
                            }
                            v += e.value;
                        }
                    } else {
                        break;
                    }
                }
                if (v.length) {
                    if (a.cadParameterName) {
                        cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                    }
                    cds.cart.addCustomAttribute(a.label, v);
                }
            } else if (a.dataType === 'text') {
                e = document.getElementById('cds-dv-' + id);
                if (e) {
                    v = e.value;
                    if (v) {
                        if (a.cadParameterName) {
                            cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                        }
                        cds.cart.addCustomAttribute(a.label, v);
                    }
                }
            }
            if (typeof cdsHandleChangeDynamicAttribute === 'function') {
                cdsHandleChangeDynamicAttribute(id, a, v);
            }
        }

        TopFloor.Cds.Components.ConfigurableAttributes.setCustomProductLabel(TopFloor.Cds.State.ConfigurableAttributes);
    };

    TopFloor.Cds.Components.ConfigurableAttributes.setCustomProductLabel = function (parameters) {
        var i, j, attributes, a, e, pn, v;

        if (typeof cdsGetCustomProductNumber === 'function') {
            attributes = {};
            for (i in cds.productAttributes) {
                a = {};
                for (j in cds.productAttributes[i]) {
                    a[j] = cds.productAttributes[i][j];
                }
                attributes[i] = a;
            }

            for (i in attributes) {
                a = attributes[i];
                if (a.dataType === 'range') {
                    a.value = document.getElementById('cds-dv-' + i).value;
                } else if (a.dataType === 'list') {
                    e = document.getElementById('cds-dv-' + i);
                    a.value = e.options[e.selectedIndex].value;
                } else if (a.dataType === 'multilist') {
                    v = [];
                    for (j = 0; j < 100; j++) {
                        e = document.getElementById('cds-dv-' + i + '-' + j);
                        if (e) {
                            if (e.checked) {
                                v.push(e.value);
                            }
                        } else {
                            break;
                        }
                    }
                    a.value = v;
                } else if (a.dataType === 'text') {
                    a.value = document.getElementById('cds-dv-' + i).value;
                }
            }
            pn = cdsGetCustomProductNumber(parameters.productId, parameters.categoryId, attributes);
            if (pn) {
                e = document.getElementsByName('cds-product-number');
                for (i = 0; i < e.length; i++) {
                    e[i].innerHTML = pn;
                }
                if (cds.CADRequester) {
                    cds.CADRequester.setCADResultFileName(pn);
                }
            }
        }
    };
})(jQuery);

function _cdsHandleChangeDynamicAttribute(id, doNotCallCustom) {
    var i, e, v, min, max, a = cds.productAttributes[id];

    if (a) {
        if (a.dataType === "range") {
            min = a.value && a.value.min;
            max = a.value && a.value.max;
            if (min && max) {
                e = document.getElementById("cds-dv-" + id);
                if (e) {
                    v = parseFloat(e.value);
                    if (isNaN(v) || v < min || v > max) {
                        alert("Please enter a value between " + min + " and " + max + ".");
                        // can't use a.value.default on IE9 compat view for some reason
                        v = a["value"]["default"] || "";
                        e.value = v;
                        e.focus();
                    } else {
                        v = (Math.round(v * Math.pow(10, a.precision)) / Math.pow(10, a.precision))
                            .toFixed(a.precision);
                        e.value = v;
                    }
                    if (a.cadParameterName) {
                        cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                    }

                    cds.cart.addCustomAttribute(a.label, v);
                }
            }
        } else if (a.dataType === "list") {
            e = document.getElementById("cds-dv-" + id);
            if (e) {
                v = e.options[e.selectedIndex].value;
                if (v) {
                    if (a.cadParameterName) {
                        cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                    }

                    cds.cart.addCustomAttribute(a.label, v);
                }
            }
        } else if (a.dataType === "multilist") {
            v = "";
            for (i = 0; i < 100; i++) {
                e = document.getElementById("cds-dv-" + id + "-" + i);
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
            if (v.length) {
                if (a.cadParameterName) {
                    cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                }

                cds.cart.addCustomAttribute(a.label, v);
            }
        } else if (a.dataType === "text") {
            e = document.getElementById("cds-dv-" + id);
            if (e) {
                v = e.value;
                if (v) {
                    if (a.cadParameterName) {
                        cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                    }

                    cds.cart.addCustomAttribute(a.label, v);
                }
            }
        }

        if (typeof cdsHandleChangeDynamicAttribute === "function"
            && !doNotCallCustom) {
            cdsHandleChangeDynamicAttribute(id, a, v);
        }
        _cdsSetCustomProductLabel();
    }
}
