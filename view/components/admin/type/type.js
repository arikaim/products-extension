/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductTypeControlPanel() {
    
    this.add = function(formId, onSuccess, onError) {
        return arikaim.post('/api/products/admin/product/type/add',formId, onSuccess, onError);          
    };

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/products/admin/product/type/update',formId, onSuccess, onError);          
    };
    
    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/products/admin/product/type/delete/' + uuid, onSuccess, onError);          
    };

    this.init = function() {       
        arikaim.ui.tab('.product-type-tab-item','product_type_content');
    };

    this.initEditForm = function() {
        arikaim.ui.form.onSubmit('#product_type_form',function() {
            return productType.update('#product_type_form');
        },function(result) {
            arikaim.ui.form.showMessage(result.message);         
        },function(error) {
        });    
    }
}

var productType = new ProductTypeControlPanel();

$(document).ready(function() {
    productType.init();
});