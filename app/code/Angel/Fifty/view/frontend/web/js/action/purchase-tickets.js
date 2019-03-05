/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/storage',
    'Magento_Ui/js/model/messageList',
    'Magento_Customer/js/customer-data',
    'Angel_Fifty/js/model/ticket',
    'Angel_Fifty/js/model/fifty',
    'mage/translate'
], function ($, storage, globalMessageList, customerData, ticket, fifty, $t) {
    'use strict';

    var callbacks = [],

        /**
         * @param {Object}  purchaseData
         * @param {String} redirectUrl
         * @param {*} isGlobal
         * @param {Object} messageContainer
         */
        action = function ( purchaseData, redirectUrl, isGlobal, messageContainer) {
            messageContainer = messageContainer || globalMessageList;
            var id = purchaseData.id?purchaseData.id:purchaseData.product;
            return storage.post(
                'fifty/ticket/purchase/id/'+id+'/qty/'+purchaseData.qty,
                JSON.stringify(purchaseData),
                isGlobal
            ).done(function (response) {
                if (response.errors) {
                    messageContainer.addErrorMessage(response);
                    callbacks.forEach(function (callback) {
                        callback(purchaseData);
                    });
                } else {
                    callbacks.forEach(function (callback) {
                        callback(purchaseData);
                    });
                    ticket.purchaseMessage(response.message);
                    ticket.purchaseSuccess(response.success);
                    if(response.success) {
                        fifty.currentPot(parseFloat(fifty.currentPot()) + parseFloat(response.data.price));
                        fifty.id(response.data.product_id);
                        var tickets = fifty.tickets();
                        tickets.push(response.data);
                        fifty.tickets(tickets);
                    }
                    setTimeout(function () {
                        ticket.purchaseMessage('');
                        ticket.purchaseSuccess(null);
                    },10000);

                    customerData.invalidate(['customer']);
                }
            }).fail(function () {
                messageContainer.addErrorMessage({
                    'message': $t('Could not purchase tickets. Please try again later')
                });
                callbacks.forEach(function (callback) {
                    callback(purchaseData);
                });
            });
        };

    /**
     * @param {Function} callback
     */
    action.registerPurchaseCallback = function (callback) {
        callbacks.push(callback);
    };

    return action;
});
