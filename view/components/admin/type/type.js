'use strict';

function ProductTypeControlPanel() {
    
    this.add = function(formId, onSuccess, onError) {
        return arikaim.post('/api/admin/products/type/add',formId, onSuccess, onError);          
    };

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/products/type/update',formId, onSuccess, onError);          
    };
    
    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/products/type/delete/' + uuid, onSuccess, onError);          
    };
}

var productType = new ProductTypeControlPanel();

arikaim.component.onLoaded(function() {
   
});