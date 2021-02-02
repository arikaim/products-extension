'use strict';

$(document).ready(function() {
    safeCall('importProductsView',function(obj) {
        obj.initRows();
    },true);    
});