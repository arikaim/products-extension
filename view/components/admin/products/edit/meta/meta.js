'use strict';

arikaim.component.onLoaded(function() {
    $('#choose_language').dropdown({
        onChange: function(value) {
            var productUuid = $('#meta_content').attr('product-uuid');
            arikaim.page.loadContent({
                id: 'meta_content',
                component: 'products::admin.products.edit.meta.form',
                params: { 
                    uuid: gameUuid,
                    language: value 
                }
            });
        }
    });
});