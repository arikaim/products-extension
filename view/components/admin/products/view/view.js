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
        return {
            search: {
                type_id: $('.product-type').val(),
                show_deleted: $('.show-deleted').val()
            }          
        }
    };

    this.init = function() {
        arikaim.ui.loadComponentButton('.product-action');
        this.loadMessages('products::admin.messages');

        $('.product-type').on('change', function() {
            var value = $(this).val();
         
            search.setSearch(self.getSearchData(),'products',function(result) {                  
                self.loadList();
            });            
        });

        $('.show-deleted').on('change', function() {
           
                search.setSearch(self.getSearchData(),'products',function(result) {                  
                    self.loadList();
                });
          
            // uncheded
                search.setSearch(self.getSearchData(),'products',function(result) {                  
                    self.loadList();
                });
         
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
            arikaim.page.loadContent({
                id: 'product_details',     
                component: 'products::admin.products.edit',
                params: { 
                    uuid: result.uuid
                }                       
            });
        },'onProductAdd');

        arikaim.events.on('product.update',function(result) {      
            arikaim.page.loadContent({
                id: 'row_' + result.uuid,    
                replace: true, 
                component: 'products::admin.products.view.item',
                params: { 
                    uuid: result.uuid
                }                       
            },function() {
                self.initRows();
            });
        },'onProductUpdate');
    };

    this.loadItem = function(uuid, prepend) {
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
          //  paginator.setPage(1,'products',function(result) {
                //paginator.reload();
          //  });          
        });
    };

    this.initRows = function() {
        arikaim.ui.loadComponentButton('.product-action');
        
        $('.status-dropdown').on('change', function() {
            var value = $().val();            
            var uuid = $(this).attr('uuid');

            products.setStatus(uuid,value);           
        });    

        arikaim.ui.button('.product-details',function(element) {
            var uuid = $(element).attr('uuid');
            $('#product_details').fadeIn(500);

            arikaim.page.loadContent({
                id: 'product_details',
                component: 'products::admin.products.details',
                params: { 
                    uuid: uuid 
                }
            });   
        });
       
        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('messages.remove.content'),{ title: title });

            modal.confirmDelete({ 
                title: self.getMessage('messages.remove.title'),
                description: message
            },function() {
                productsApi.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#row_' + uuid);  
                    arikaim.page.toastMessage(result.message);  
                });
            });
        });

        arikaim.ui.button('.edit-button',function(element) { 
            arikaim.page.loadContent({
                id: 'product_details',
                component: 'products::admin.products.edit',
                params: { 
                    uuid: $(element).attr('uuid')    
                }
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
}); 