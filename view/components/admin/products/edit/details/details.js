'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit('#product_form',function() {
        return productsApi.update('#product_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);       
    },function(error) {
    });
});