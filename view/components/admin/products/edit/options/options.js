'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit('#product_options_form',function() {
        return productsApi.updateOptions('#product_options_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});