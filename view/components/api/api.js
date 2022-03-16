/**
 *  Arikaim  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
 'use strict';

 function ProductsApi() {
    
    this.getDetails = function(uuid, onSuccess, onError) {
        return arikaim.get('/api/products/product/details/' + uuid,onSuccess,onError);          
    };

    this.getPrice = function(uuid, onSuccess, onError) {
        return arikaim.get('/api/products/price/' + uuid,onSuccess,onError);          
    };
}

var productsApi = new ProductsApi();
