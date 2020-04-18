'use strict';

$(document).ready(function() {     
    safeCall('productsView',function(obj) {
        obj.initRows();
    },true);   
}); 