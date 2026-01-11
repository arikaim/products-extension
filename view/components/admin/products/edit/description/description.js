'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.addRules("#product_description_form");

    arikaim.ui.form.onSubmit('#product_description_form',function() {
        return productsApi.updateDescription('#product_description_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);            
    },function(error) {
    });
});