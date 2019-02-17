/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'ko',
    'mage/mage',
    'Magento_Catalog/product/view/validation',
    'Angel_Fifty/js/action/purchase-tickets',
    'Magento_Customer/js/customer-data',
    'mage/validation'
], function ($, ko, mage, validation, purchaseAction, customerData) {
    'use strict';

    $.widget('fifty.fiftyValidate', {
        isLoading: ko.observable(false),
        options: {
            bindSubmit: false,
            radioCheckboxClosest: '.nested'
        },
        isLoggedIn : function () {
            var customer = customerData.get('customer');
            return customer && customer.firstname;
        },

        /**
         * Uses Magento's validation widget for the form object.
         * @private
         */
        _create: function () {
            var self = this;
            var bindSubmit = this.options.bindSubmit;

            this.element.validation({
                radioCheckboxClosest: this.options.radioCheckboxClosest,

                /**
                 * Uses catalogAddToCart widget as submit handler.
                 * @param {Object} form
                 * @returns {Boolean}
                 */
                submitHandler: function (form) {
                    if (!self.isLoggedIn()){
                        window.location.href = self.options.loginUrl;
                        return;
                    }
                    if (self.isLoading()){
                        return false;
                    }
                    var formElement = $('#'+form.id),
                    formDataArray = formElement.serializeArray();
                    var purchaseData = [];
                    formDataArray.forEach(function (entry) {
                        purchaseData[entry.name] = entry.value;
                    });

                    if (formElement.validation() &&
                        formElement.validation('isValid')
                    ) {
                        self.isLoading(true);
                        $('#product-addtocart-button').addClass('disabled');
                        purchaseAction(purchaseData);
                    }
                    return false;
                }
            });
            purchaseAction.registerPurchaseCallback(function () {
                self.isLoading(false);
                $('#product-addtocart-button').removeClass('disabled')
            });
        }
    });

    return $.fifty.fiftyValidate;
});
