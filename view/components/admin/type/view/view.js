/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductTypeView() {
    var self = this;

    this.init = function() {
        paginator.init('product_type_rows');   
    };

    this.initRows = function() {
       
        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(productsControlPanel.messages.type.remove.content,{ title: title });
            
            modal.confirmDelete({ 
                title: productsControlPanel.messages.type.remove.title,
                description: message
            },function() {
                productType.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#' + uuid); 
                    arikaim.page.toastMessage(result.message);    
                });
            });
        });

        arikaim.ui.button('.edit-button',function(element) {
            var uuid = $(element).attr('uuid');    
            arikaim.ui.setActiveTab('#edit_product','.product-type-tab-item');
            arikaim.page.loadContent({
                id: 'product_type_content',
                component: 'products::admin.type.edit',
                params: { uuid: uuid }
            });          
        });
    };
};

var productTypeView = new ProductTypeView();

$(document).ready(function() {  
    productTypeView.init();
    productTypeView.initRows();  
}); 