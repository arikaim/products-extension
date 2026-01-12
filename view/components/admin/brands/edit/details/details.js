'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.ui.form.onSubmit('#brand_form',function() {
        return brandsApi.update('#brand_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);         
        arikaim.events.emit('brand.update',result.uuid);    
    });    
});