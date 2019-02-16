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

            return storage.post(
                'fifty/ticket/purchase/sku/'+purchaseData.sku+'/qty/'+purchaseData.qty,
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
                    fifty.currentPot(fifty.currentPot() + Number.parseFloat(response.data.price));
                    fifty.id(response.data.product_id)

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
