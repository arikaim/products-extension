'use strict';

$(document).ready(function() {

    arikaim.ui.button('.import-order',function(element) {
        var id = $(element).attr('external-id');
        var driverName = $(element).attr('driver-name');

        var data = {
            external_id: id,
            driver_name: driverName
        };

        return arikaim.post('/api/orders/admin/external/import',data,function(result) {
            arikaim.page.loadContent({
                id: 'external_order_details',
                component: 'orders::admin.orders.details',
                params: { 
                    uuid: result.uuid                  
                }
            }); 
        });
    });

});
