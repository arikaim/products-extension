'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit('#product_type_form',function() {
        return productType.add('#product_type_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
        
        arikaim.page.loadContent({
            id: 'product_type_content',
            component: 'products::admin.type.view'           
        });         
    },function(error) {
    });
});