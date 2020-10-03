/**
 *  Arikaim  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductsControlPanel() {
    var self = this;
    this.messages = {};

    this.loadMessages = function() {
        arikaim.component.loadProperties('products::admin.messages',function(params) {            
            self.messages = params.messages;
        });
    };

    this.init = function() {
        arikaim.ui.tab();      
    };
}

if (isEmpty(productsControlPanel) == true) {
    var productsControlPanel = new ProductsControlPanel();
    productsControlPanel.loadMessages();
}


$(document).ready(function() {
    productsControlPanel.init();
});