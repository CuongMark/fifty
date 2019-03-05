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
    'Magento_Ui/js/modal/confirm',
    'mage/validation'
], function ($, ko, mage, validation, purchaseAction, customerData, confirmation) {
    'use strict';

    $.widget('fifty.fiftyValidate', {
        isLoading: ko.observable(false),
        options: {
            bindSubmit: false,
            radioCheckboxClosest: '.nested'
        },
        isLoggedIn : function () {
            var customer = customerData.get('customer');
            return customer && customer().firstname;
        },

        submitPurchaseRequest : function (form) {
            var self = this;
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
                        return false;
                    }
                    if (self.isLoading()){
                        return false;
                    }
                    confirmation({
                        title: 'Accept Purchase',
                        content: 'Are you sure to purchase '+ $('#qty').val() +' tickets?',
                        actions: {
                            confirm: function () {
                                self.submitPurchaseRequest(form);
                                return false;
                            },
                            cancel: function () {
                                return false;
                            }
                        }
                    });
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
