/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
*/
'use strict';

function ImportProductsView() {
    var self = this;
    this.messages = null;

    this.init = function() {
        //paginator.init('orders_rows');   
        
        $('.drivers-dropdown').dropdown({
            onChange: function(selected) {

            }
        });
        //this.loadMessages();
    };

    this.loadMessages = function() {
        if (isObject(this.messages) == true) {
            return;
        }

        arikaim.component.loadProperties('products::admin.import.view',function(params) { 
            self.messages = params.messages;
        }); 
    };

    this.initRows = function() {    
    
        $('.status-dropdown').dropdown({
            onChange: function(value) {
                var uuid = $(this).attr('uuid');
                adsControlPanel.setStatus(uuid,value);               
            }
        });

        arikaim.ui.button('.details-button',function(element) {
            var uuid = $(element).attr('uuid');
           
        });
    }
}

var importProductsView = new ImportProductsView();

$(document).ready(function() {
    importProductsView.init();   
    importProductsView.initRows();
});