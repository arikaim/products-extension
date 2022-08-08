/**
 *  Arikaim  
<<<<<<< HEAD
 *  @copyright  Copyright (c)  <info@arikaim.com>
=======
 *  @copyright  Copyright (c) Intersoft Ltd <info@arikaim.com>
>>>>>>> 90df598a26451cb4ca64ad719d03da3cb7f02df9
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
 'use strict';

 function ProductsApi() {
    
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
}

var productsApi = new ProductsApi();
