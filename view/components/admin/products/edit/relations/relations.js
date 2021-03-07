'use strict';

arikaim.component.onLoaded(function() {
    $('#packages_dropdown').dropdown({});

    arikaim.ui.button('.add-relation',function(element) {
        var productId = $(element).attr('product-id');
        var type = $(element).attr('type');
        var relationId = $('#packages_dropdown').dropdown('get value');

        return relations.add('ProductRelations','products',productId,type,relationId,function(result) {
            
            arikaim.page.loadContent({
                id: 'relations_items',
                component: 'system:admin.orm.relations.view',
                params: { 
                    extension: 'products',
                    model: 'ProductRelations',
                    id: productId
                }
            });  
        });
    });
});