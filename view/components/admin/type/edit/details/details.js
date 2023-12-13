'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit('#product_type_form',function() {
        return productType.update('#product_type_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);         
    },function(error) {
    });    
});