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

        this.initRows();  
    };

    this.initRows = function() {       
        arikaim.ui.loadComponentButton('.brand-action');

        $('.status-dropdown').on('change', function() {
            var value = $(this).val();
            var uuid = $(this).attr('uuid');

            brandsApi.setStatus(uuid,value);
        });    

        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('brand.remove.content'),{ title: title });
            
            arikaim.ui.getComponent('confirm_delete').open(function() {
                brandsApi.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#row_' + uuid); 
                    arikaim.ui.getComponent('toast').show(result.message);   
                });
            },message);
        });
    };
};

var productBrandsView = createObject(ProductBrandsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    productBrandsView.init();
}); 