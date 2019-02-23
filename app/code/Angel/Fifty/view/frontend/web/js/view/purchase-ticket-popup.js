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
    'Magento_Ui/js/modal/confirm',
    'mage/validation'
], function ($, ko, Component, loginAction, customerData, fifty, ticket, purchaseTicketPopup, priceUtils, $t, url, confirmation) {
    'use strict';

    return Component.extend({
        modalWindow: null,
        fifty: fifty,
        qty: purchaseTicketPopup.qty,
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
        tickets: fifty.tickets,
        formatPrice: function(price){
            return priceUtils.formatPrice(price, this.priceFormat);
        },
        defaults: {
            template: 'Angel_Fifty/purchase-ticket-popup'
        },

        /**
         * Init
         */
        initialize: function () {
            var self = this;
            this._super();
            if (typeof this.currentFiftyPrice != "undefined"){
                this.productPrice(this.currentFiftyPrice);
            }
            if (typeof this.currentPotPrice != "undefined"){
                this.currentPot(this.currentPotPrice);
            }
            this.customer = customerData.get('customer');
            this.suggestCreditBalance = ko.computed(function(){
                var balance = priceUtils.formatPrice(self.customer().creditBalance, self.priceFormat);
                if (!self.customer().creditBalance){
                    return $t('Please buy credit to purchase');
                }
                return $t('You have got ') + balance + $t(' credits. The maximum tickets to purchase is ') + Number.parseInt(self.customer().creditBalance/self.productPrice());
            });
            this.hasTickets = ko.computed(function(){
                return self.tickets().length;
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
            url.setBaseUrl(window.authenticationPopup.baseUrl);
            loginAction.registerPurchaseCallback(function () {
                self.isLoading(false);
            });
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

        submitPurchaseRequest : function (formUiElement, event) {
            var purchaseData = {},
                formElement = $(event.currentTarget),
                formDataArray = formElement.serializeArray();

            event.stopPropagation();
            formDataArray.forEach(function (entry) {
                purchaseData[entry.name] = entry.value;
            });
            purchaseData['sku'] = this.productSku();
            purchaseData['id'] = this.productId();

            if (formElement.validation() &&
                formElement.validation('isValid')
            ) {
                this.isLoading(true);
                loginAction(purchaseData);
            }
            return false;
        },

        /**
         * Provide login action
         *
         * @return {Boolean}
         */
        purchase: function (formUiElement, event) {
            var self = this;
            confirmation({
                title: 'Accept Purchase',
                content: 'Are you sure to purchase '+ $('#qty').val() +' tickets?',
                actions: {
                    confirm: function () {
                        self.submitPurchaseRequest(formUiElement, event);
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
});
