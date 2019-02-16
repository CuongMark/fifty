/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Angel_Fifty/fifty-information'
        },

        /** @inheritdoc */
        initialize: function () {
            var self = this;
            this._super();
            var interval = setInterval(function() {
                self.second;
                console.log('333');
            }, 1000);
        },
    });
});
