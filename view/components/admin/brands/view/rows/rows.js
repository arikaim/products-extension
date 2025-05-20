'use strict';

arikaim.component.onLoaded(function() {   
    safeCall('productBrandsView',function(obj) {
        obj.initRows();
    },true);   
}); 