'use strict';

arikaim.component.onLoaded(function() {
    safeCall('importProductsView',function(obj) {
        obj.initRows();
    },true);    
});