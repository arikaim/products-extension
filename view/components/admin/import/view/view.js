/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
*/
'use strict';

function ImportProductsView() {
    var self = this;

    this.init = function() {
        //paginator.init('orders_rows');   
        
        $('.drivers-dropdown').dropdown({
            onChange: function(selected) {
            }
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

arikaim.component.onLoaded(function() {
    importProductsView.init();   
    importProductsView.initRows();
});