'use strict';

arikaim.component.onLoaded(function() {    
    safeCall('productsView',function(obj) {
        obj.initRows();
    },true);   
}); 