'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.button('.order-details-button',function(element) {
        var code = $('#code').val();
        var driverName = $(element).attr('driver-name');

        return arikaim.page.loadContent({
            id: 'sales_order_details',
            component: 'orders::admin.external.details.fetch',
            params: { 
                code: code,
                driver_name: driverName
            }
        }); 
    });   
});
