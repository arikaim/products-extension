/**
 *  Arikaim  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductsControlPanel() {
    var self = this;
    this.messages = null;

    this.loadMessages = function() {
        if (isObject(this.messages) == true) {
            return;
        }

        arikaim.component.loadProperties('products::admin.messages',function(params) {            
            self.messages = params.messages;
        });
    };

    this.init = function() {
        arikaim.ui.tab();    
        this.loadMessages();  
    };
}

var productsControlPanel = new ProductsControlPanel();

$(document).ready(function() {
    productsControlPanel.init();
});