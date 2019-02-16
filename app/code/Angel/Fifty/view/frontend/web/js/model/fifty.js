/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko'
], function ($, ko) {
    'use strict';

    return {
        name: ko.observable(''),
        image: ko.observable(''),
        sku: ko.observable(''),
        price: ko.observable(''),
        startPot: ko.observable(''),
        start_at: ko.observable(''),
        finish_at: ko.observable(''),
        currentPot: ko.observable(''),
        winning_number: ko.observable(''),
        status: ko.observable(''),

        updateData: function ($data) {
            if ($data.name){
                this.name($data.name);
            }
            if ($data.image){
                this.image($data.image);
            }
            if ($data.sku){
                this.sku($data.sku);
            }
            if ($data.price){
                this.price($data.price);
            }
            if ($data.startPot){
                this.startPot($data.startPot);
            }
            if ($data['start_at']){
                this.start_at($data['start_at']);
            }
            if ($data['finish_at']){
                this.finish_at($data['finish_at']);
            }
            if ($data.currentPot){
                this.currentPot($data.currentPot);
            }
            if ($data['winning_number']){
                this.winning_number($data['winning_number']);
            }
            if ($data['status']){
                this.status($data['status']);
            }
        }
    };
});
