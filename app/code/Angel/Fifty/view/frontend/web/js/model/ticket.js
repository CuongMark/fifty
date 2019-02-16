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
        purchaseSuccess: ko.observable(null),
        purchaseMessage: ko.observable(''),
        start: ko.observable(''),
        end: ko.observable(''),
        price: ko.observable(''),
        status: ko.observable(''),
    };
});
