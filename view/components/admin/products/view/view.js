/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductsView() {
    var self = this;

    this.getSearchData = function() {      
        var typeId = $('.product-type').dropdown('get value');
        var deleted = $('.show-deleted').checkbox('is checked');

        return {
            search: {
                type_id: typeId,
                show_deleted: deleted
            }          
        }
    };

    this.init = function() {
        arikaim.ui.loadComponentButton('.add-product');

        this.loadMessages('products::admin.messages');

        paginator.init('product_rows');   

        $('.product-type').dropdown({
            onChange: function(value) {
                search.setSearch(self.getSearchData(),'products',function(result) {                  
                    self.loadList();
                });
            }
        });

        $('.show-deleted').checkbox({
            onChecked: function() {
                search.setSearch(self.getSearchData(),'products',function(result) {                  
                    self.loadList();
                });
            },
            onUnchecked: function() {
                search.setSearch(self.getSearchData(),'products',function(result) {                  
                    self.loadList();
                });
            }
        });

        search.init({
            id: 'product_rows',
            component: 'products::admin.products.view.rows',
            event: 'product.search.load'
        },'products');  
        
        arikaim.events.on('product.search.load',function(result) {      
            paginator.reload();
            self.initRows();    
        },'productSearch');

        arikaim.events.on('product.add',function(result) {      
            self.loadItem(result.uuid,true);    
        },'onProductAdd');
    };

    this.loadItem = function(uuid, prepend) {
        console.log(uuid);
        
        arikaim.page.loadContent({
            id: 'product_rows',     
            prepend: prepend,   
            component: 'products::admin.products.view.item',
            params: { 
                uuid: uuid
            }                       
        },function(result) {
            self.initRows();           
        });
    };

    this.loadList = function() {
        arikaim.page.loadContent({
            id: 'product_rows',           
            component: 'products::admin.products.view.rows'                         
        },function(result) {
            paginator.setPage(1,'products',function(result) {
                paginator.reload();
            });          
        });
    };

    this.initRows = function() {
        $('.status-dropdown').dropdown({
            onChange: function(value) {               
                var uuid = $(this).attr('uuid');
                products.setStatus(uuid,value);
            }
        });    

        arikaim.ui.button('.product-details',function(element) {
            var uuid = $(element).attr('uuid');
            $('#product_details').fadeIn(500);

            arikaim.page.loadContent({
                id: 'product_details',
                component: 'products::admin.products.details',
                params: { uuid: uuid }
            });   
        });
       
        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });

            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
                description: message
            },function() {
                products.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#' + uuid);  
                    arikaim.page.toastMessage(result.message);  
                });
            });
        });

        arikaim.ui.button('.edit-button',function(element) {
            var uuid = $(element).attr('uuid');    
           
            arikaim.page.loadContent({
                id: 'product_details',
                component: 'products::admin.products.edit',
                params: { uuid: uuid }
            });          
        });

        arikaim.ui.button('.restore-button',function(element) {
            var uuid = $(element).attr('uuid');
    
            products.restoreProduct(uuid,function(result) {
                arikaim.ui.table.removeRow('#' + uuid);  
                arikaim.page.toastMessage(result.message);  
            });     
        });
    };
};

var productsView = createObject(ProductsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    productsView.init();
    productsView.initRows();  
}); 