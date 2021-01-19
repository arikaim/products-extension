'use strict';

$(document).ready(function() {  
    $('.product-type').dropdown({
        onChange: function(value) {             
            arikaim.page.loadContent({
                id: 'form_content',
                component: 'products::admin.type.form',
                params: { uuid: value }
            },function(result) {
                productType.initEditForm();   
            });    
        }
    });
});