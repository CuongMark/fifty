/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Angel_Fifty/js/model/purchase-ticket-popup'
], function ($, purchaseTicketPopup) {
    'use strict';

    $.widget('angel.fiftyInforamtion', {
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
                purchaseTicketPopup.showModal();
            });
        },
    });

    return $.angel.fiftyInforamtion;
});
