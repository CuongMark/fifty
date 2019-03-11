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

        day: ko.observable(0),
        hour: ko.observable(0),
        minute: ko.observable(0),
        second: ko.observable(0),
        endTime: Date.now() + 100000,
        defaults: {
            template: 'Angel_Fifty/countdown'
        },

        /** @inheritdoc */
        initialize: function () {
            var self = this;
            this._super();
            var interval = setInterval(function() {
                var finished = self.updateCountdown();
                if (finished){
                    clearInterval(interval);
                    window.location.reload();
                }
            }, 1000);
        },

        updateCountdown: function() {
            var seconds = Math.floor((this.endTime - Date.now())/1000);
            if (seconds < 0){
                return true;
            }
            var day = Math.floor(seconds/86400);
            var hour = Math.floor((seconds - 86400*day)/3600);
            var minute = Math.floor((seconds - 86400*day - 3600*hour)/60);
            var second = seconds%60;
            this.day(day);
            this.hour(hour);
            this.minute(minute);
            this.second(second);
            return false;
        }
    });
});
