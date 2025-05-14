'use strict';

function ProductsApi() {
    
    this.restoreProduct = function(uuid, onSuccess, onError) {
        return arikaim.put('/api/products/restore/' + uuid,onSuccess,onError);          
    };

    this.updateDescription = function(formId, onSuccess, onError) {
        return arikaim.put('/api/products/update/description',formId, onSuccess, onError);          
    };

    this.updateMetaTags = function(formId, onSuccess, onError) {
        return arikaim.put('/api/products/update/meta',formId,onSuccess,onError);          
    };

    this.setStatus = function(uuid, status, onSuccess, onError) {
        var data = {
            uuid: uuid,
            status: status
        };

        return arikaim.put('/api/admin/products/status',data,onSuccess,onError);          
    };   

    this.add = function(formId, onSuccess, onError) {
        return arikaim.post('/api/products/product/add',formId,onSuccess,onError);          
    };

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/products/product/update',formId,onSuccess,onError);          
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/products/product/delete/' + uuid,onSuccess,onError);          
    };

    this.getDetails = function(uuid, onSuccess, onError) {
        return arikaim.get('/api/products/product/details/' + uuid,onSuccess,onError);          
    };

    this.getPrice = function(uuid, onSuccess, onError) {
        return arikaim.get('/api/products/price/' + uuid,onSuccess,onError);          
    };

    this.updatePrice = function(formId, onSuccess, onError) {
        return arikaim.put('/api/products/price/update',formId, onSuccess, onError);          
    };

    this.updateOptions = function(formId, onSuccess, onError) {
        return arikaim.put('/api/products/options/update',formId, onSuccess, onError);          
    };
}

var productsApi = new ProductsApi();
