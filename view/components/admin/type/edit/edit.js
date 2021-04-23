'use strict';

arikaim.component.onLoaded(function() {
    $('.product-type').dropdown({
        onChange: function(value) {             
            arikaim.page.loadContent({
                id: 'product_type_edit_content',
                component: 'products::admin.type.edit.tabs',
                params: { uuid: value }
            },function(result) {
                productType.initEditForm();   
            });    
        }
    });
});