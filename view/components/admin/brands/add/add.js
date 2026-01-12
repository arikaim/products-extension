'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit('#brand_form',function() {
        return brandsApi.add('#brand_form');
    },function(result) {
        arikaim.ui.getComponent('toast').show(result.message);      
        arikaim.events.emit('brand.add',result.uuid);    

        arikaim.page.loadContent({
            id: 'brand_details',
            component: 'products::admin.brands.edit',
            params: {
                uuid: result.uuid
            }           
        });         
    });
});