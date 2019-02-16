/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/form',
    'Angel_Fifty/js/action/purchase-tickets',
    'Magento_Customer/js/customer-data',
    'Angel_Fifty/js/model/fifty',
    'Angel_Fifty/js/model/ticket',
    'Angel_Fifty/js/model/purchase-ticket-popup',
    'Magento_Catalog/js/price-utils',
    'mage/translate',
    'mage/url',
    'Magento_Ui/js/modal/alert',
    'mage/validation'
], function ($, ko, Component, loginAction, customerData, fifty, ticket, purchaseTicketPopup, priceUtils, $t, url, alert) {
    'use strict';

    return Component.extend({
        modalWindow: null,
        fifty: fifty,
        isLoading: ko.observable(false),
        isSuccess: ticket.purchaseSuccess,
        message: ticket.purchaseMessage,
        productName: fifty.name,
        productId: fifty.id,
        productImage: fifty.image,
        productSku: fifty.sku,
        productPrice : fifty.price,
        currentPot : fifty.currentPot,
        priceFormat : window.checkoutConfig?window.checkoutConfig.priceFormat:{"pattern":"$%s","precision":2,"requiredPrecision":2,"decimalSymbol":".","groupSymbol":",","groupLength":3,"integerRequired":false},
        timeLeft: 0,
        day: ko.observable(0),
        hour: ko.observable(0),
        minute: ko.observable(0),
        second: ko.observable(0),
        defaults: {
            template: 'Angel_Fifty/purchase-ticket-popup'
        },

        /**
         * Init
         */
        initialize: function () {
            var self = this;
            this._super();
            this.customer = customerData.get('customer');
            this.suggestCreditBalance = ko.computed(function(){
                var balance = priceUtils.formatPrice(self.customer().creditBalance, self.priceFormat);
                if (!self.customer().creditBalance){
                    return $t('Please buy credit to purchase');
                }
                return $t('You have got ') + balance + $t(' credits. The maximum tickets to purchase is ') + Number.parseInt(self.customer().creditBalance/self.productPrice());
            });
            this.productPriceFormated = ko.computed(function(){
                return priceUtils.formatPrice(self.productPrice(), self.priceFormat);
            });
            this.currentPotText = ko.computed(function(){
                var currentPotFormated = priceUtils.formatPrice(self.currentPot(), self.priceFormat);
                if(self.currentPot() && self.productId() && $('#current_pot' + self.productId() + ' .price').length) {
                    $('#current_pot' + self.productId() + ' .price')[0].innerHTML = currentPotFormated;
                }
                return $t('Current Pot: ') + currentPotFormated;
            });

            // ko.computed(function(){
            //     self.timeLeft = fifty.timeLeft;
            // });
            //
            // setInterval(function() {
            //     self.updateCountdown(--self.timeLeft);
            // }, 1000);

            url.setBaseUrl(window.authenticationPopup.baseUrl);
            loginAction.registerPurchaseCallback(function () {
                self.isLoading(false);
            });
        },

        updateCountdown: function(time){
            var days = Math.floor(time/86400);
            var hours = Math.floor((time - 86400*days)/3600);
            var minutes = Math.floor((time - 86400*days - 3600*hours)/60);
            var seconds = time%60;
            this.day(days);
            this.hour(hours);
            this.minute(minutes);
            this.second(seconds);
        },

        /** Init popup login window */
        setModalElement: function (element) {
            if (purchaseTicketPopup.modalWindow == null) {
                purchaseTicketPopup.createPopUp(element);
            }
        },

        /** Is login form enabled for current customer */
        isActive: function () {
            var customer = customerData.get('customer');

            return customer() != false; //eslint-disable-line eqeqeq
        },

        /** Show login popup window */
        showModal: function () {
            if (this.modalWindow) {
                $(this.modalWindow).modal('openModal');
            } else {
                alert({
                    content: $t('Unable to purchase tickets.')
                });
            }
        },

        /**
         * Provide login action
         *
         * @return {Boolean}
         */
        purchase: function (formUiElement, event) {
            var purchaseData = {},
                formElement = $(event.currentTarget),
                formDataArray = formElement.serializeArray();

            event.stopPropagation();
            formDataArray.forEach(function (entry) {
                purchaseData[entry.name] = entry.value;
            });
            purchaseData['sku'] = this.productSku();

            if (formElement.validation() &&
                formElement.validation('isValid')
            ) {
                this.isLoading(true);
                loginAction(purchaseData);
            }

            return false;
        }
    });
});
