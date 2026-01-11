/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductBrandsView() {
    var self = this;

    this.init = function() {
        this.loadMessages('products::admin.messages');
        arikaim.ui.loadComponentButton('.create-brand');
        paginator.init('brand_rows');   

        arikaim.events.on('brand.add',function(uuid) {      
            arikaim.page.loadContent({
                id: 'brand_rows',     
                prepend: true,
                component: 'products::admin.brands.view.item',
                params: { 
                    uuid: uuid
                }                       
            },function() {
                self.initRows();
            });
        },'onBrandAdd');

        arikaim.events.on('brand.update',function(uuid) {                  
            arikaim.page.loadContent({
                id: 'row_' + uuid,     
                replace: true,
                component: 'products::admin.brands.view.item',
                params: { 
                    uuid: uuid
                }                       
            },function() {
                self.initRows();
            });
        },'onBrandUpdate');

        self.initRows();  
    };

    this.initRows = function() {       
        arikaim.ui.loadComponentButton('.brand-action');

        $('.status-dropdown').dropdown({
            onChange: function(value) {               
                var uuid = $(this).attr('uuid');
                brandsApi.setStatus(uuid,value);
            }
        });    

        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('messages.brand.remove.content'),{ title: title });
            
            modal.confirmDelete({ 
                title: self.getMessage('messages.brand.remove.title'),
                description: message
            },function() {
                brandsApi.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#row_' + uuid); 
                    arikaim.page.toastMessage(result.message);    
                });
            });
        });
    };
};

var productBrandsView = createObject(ProductBrandsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    productBrandsView.init();
}); 