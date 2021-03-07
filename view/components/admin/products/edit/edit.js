'use strict';

arikaim.component.onLoaded(function() { 
    $('.product-dropdown').on('change',function(value) {
        var selected = $(this).dropdown('get value');
        
        arikaim.page.loadContent({
            id: 'edit_product_tab',
            component: 'products::admin.products.edit.tab',
            params: { uuid: selected }
        });  
    });
});