/**
 *  Arikaim  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductsControlPanel() {
    var self = this;
  
    this.init = function() {
        arikaim.ui.tab();    
    };
}

var productsControlPanel = new ProductsControlPanel();

arikaim.component.onLoaded(function() {
    productsControlPanel.init();
});