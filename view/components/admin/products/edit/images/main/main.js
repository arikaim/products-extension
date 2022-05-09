'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.events.on('image.upload',function(result) {   
        var uuid = $('#product_image').attr('uuid');

        products.update({
            image_id: result.id,
            uuid: uuid
        },function(result) {
            return arikaim.page.loadContent({
                id: 'product_images_content',           
                component: 'products::admin.products.edit.images.main',
                params: { 
                    uuid: uuid                   
                }
            });  
        });
    },'productImageUpload');   
});