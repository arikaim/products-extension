/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ExternalProductId() {
    var self = this;

    this.init = function() {
        arikaim.ui.button('.add-external-id',function(element) {
            var uuid = $(element).attr('uuid');
            var id = $('#external_id').val();
            var driver = $('#drivers_dropdown').val();

            self.add(uuid,id,driver,function(result) {
                self.loadRows(uuid);
            });     
        });
    };

    this.loadRows = function(uuid) {
        arikaim.page.loadContent({
            id: 'external_id_rows',
            component: 'products::admin.products.edit.external.rows',
            params: { uuid: uuid }
        },function(result) {
            self.initRows();
        });  
    };  

    this.add = function(productUuid, externalId, apiDriver, onSuccess, onError) {
        return arikaim.post('/api/products/admin/external/id',{
            uuid: productUuid,
            external_id: externalId,
            api_driver: apiDriver
        },onSuccess,onError);    
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/products/admin/external/id/' + uuid,onSuccess,onError);    
    };

    this.initRows = function() {
        arikaim.ui.button('.delete-external-id',function(element) {
            var uuid = $(element).attr('uuid');
          
            self.delete(uuid,function(result) {
                self.loadRows(uuid);
            });     
        });
    };
}

var externalProductId = new ExternalProductId();

arikaim.component.onLoaded(function() {
    externalProductId.init();
});
