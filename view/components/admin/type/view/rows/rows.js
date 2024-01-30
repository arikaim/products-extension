'use strict';

arikaim.component.onLoaded(function() {   
    safeCall('productTypeView',function(obj) {
        obj.initRows();
    },true);   
}); 