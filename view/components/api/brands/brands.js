'use strict';

function ProductBrandsApi() {
    
    this.setStatus = function(uuid, status, onSuccess, onError) {
        return arikaim.put('/api/products/brand/status',{
            uuid: uuid,
            status: status
        },onSuccess,onError);          
    };   

    this.add = function(formId, onSuccess, onError) {
        return arikaim.post('/api/products/brand/add',formId,onSuccess,onError);          
    };

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/products/brand/update',formId,onSuccess,onError);          
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/products/brand/delete/' + uuid,onSuccess,onError);          
    };
    
}

var brandsApi = new ProductBrandsApi();
