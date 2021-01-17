'use strict';

$(document).ready(function() {     
    safeCall('externalProductId',function(obj) {
        obj.initRows();
    },true);   
}); 