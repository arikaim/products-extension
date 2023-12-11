'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit('#product_form',function() {
        return products.add('#product_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
        arikaim.events.emit('product.add',result);            
    },function(error) {

    });
});