'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit("#meta_tags_form",function() {     
        return productsApi.updateMetaTags('#meta_tags_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});