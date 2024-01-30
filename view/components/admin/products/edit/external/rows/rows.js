'use strict';

arikaim.component.onLoaded(function() {   
    safeCall('externalProductId',function(obj) {
        obj.initRows();
    },true);   
}); 