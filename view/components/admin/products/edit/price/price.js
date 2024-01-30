'use strict';

arikaim.component.onLoaded(function() {
    $('.option-field').popup();

    arikaim.ui.form.onSubmit('#price_form',function() {
        return productsApi.updatePrice('#price_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});