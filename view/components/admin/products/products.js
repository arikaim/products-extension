/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductsControlPanel() {
    
    this.createPriceList = function(uuid, onSuccess, onError) {
        var data = {
            uuid: uuid
        };

        return arikaim.put('/api/admin/products/price/create',data,onSuccess,onError);          
    };

    this.createOptions = function(uuid, onSuccess, onError) {
        var data = {
            uuid: uuid
        };

        return arikaim.put('/api/admin/products/create/options',data,onSuccess,onError);          
    };

    this.add = function(formId, onSuccess, onError) {
        return arikaim.post('/api/admin/products/add',formId,onSuccess,onError);          
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/products/delete/' + uuid,onSuccess,onError);          
    };

    this.restoreProduct = function(uuid, onSuccess, onError) {
        return arikaim.put('/api/admin/products/restore/' + uuid,onSuccess,onError);          
    };

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/products/update',formId, onSuccess, onError);          
    };

    this.updateDescription = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/products/update/description',formId, onSuccess, onError);          
    };

    this.updatePrice = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/products/price/update',formId, onSuccess, onError);          
    };

    this.updateMetaTags = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/products/update/meta',formId,onSuccess,onError);          
    };

    this.setStatus = function(uuid, status, onSuccess, onError) {
        var data = {
            uuid: uuid,
            status: status
        };

        return arikaim.put('/api/admin/products/status',data,onSuccess,onError);          
    };   
}

var products = new ProductsControlPanel();

arikaim.component.onLoaded(function() {
    arikaim.ui.tab('.product-tab-item','products_content');
});