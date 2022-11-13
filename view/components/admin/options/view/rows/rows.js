'use strict';

arikaim.component.onLoaded(function() {   
    safeCall('productOptionsView',function(obj) {
        obj.initRows();
    },true);   
}); 