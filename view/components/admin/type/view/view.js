/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductTypeView() {
    var self = this;

    this.init = function() {
        this.loadMessages('products::admin.messages');       
    };

    this.initRows = function() {       
        arikaim.ui.loadComponentButton('.product-type-action');

        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('messages.type.remove.content'),{ title: title });
            
            modal.confirmDelete({ 
                title: self.getMessage('messages.type.remove.title'),
                description: message
            },function() {
                productType.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#product_type_row_' + uuid); 
                    arikaim.page.toastMessage(result.message);    
                });
            });
        });
    };
};

var productTypeView = createObject(ProductTypeView,ControlPanelView);

arikaim.component.onLoaded(function() {
    productTypeView.init();
}); 