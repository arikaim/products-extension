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
            var message = arikaim.ui.template.render(self.getMessage('type.remove.content'),{ title: title });
            
            arikaim.ui.getComponent('confirm_delete').open(function() {
                productType.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#product_type_row_' + uuid); 
                    arikaim.ui.getComponent('toast').show(result.message); 
                });
            },message);
        });
    };
};

var productTypeView = createObject(ProductTypeView,ControlPanelView);

arikaim.component.onLoaded(function() {
    productTypeView.init();
}); 