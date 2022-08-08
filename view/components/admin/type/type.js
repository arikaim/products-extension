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

arikaim.component.onLoaded(function() {
    arikaim.ui.tab('.product-type-item','product_type_content');
});