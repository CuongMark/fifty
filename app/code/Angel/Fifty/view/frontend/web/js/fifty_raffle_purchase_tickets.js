/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Angel_Fifty/js/model/fifty',
    'Angel_Fifty/js/model/ticket',
    'Angel_Fifty/js/model/purchase-ticket-popup'
], function ($, fifty, ticket, purchaseTicketPopup) {
    'use strict';

    $.widget('angel.fiftyRafflePurchaseTickets', {
        options: {
            triggerEvent : 'click'
        },

        /**
         * Create sidebar.
         * @private
         */
        _create: function () {
            var self = this;
            self.element.on(self.options.triggerEvent, function() {
                var data = self.element.data();
                if (data.notloggedin){
                    window.location.href = self.options.loginUrl;
                    return;
                }
                ticket.purchaseMessage('');
                ticket.purchaseSuccess(null);
                fifty.tickets([]);
                fifty.updateData(data);
                purchaseTicketPopup.qty(1);
                purchaseTicketPopup.showModal();
            });
        },
    });

    return $.angel.fiftyRafflePurchaseTickets;
});
