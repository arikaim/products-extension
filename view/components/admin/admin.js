/**
 *  Arikaim  
 *  @copyright  Copyright (c) Intersoft Ltd <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductsControlPanel() {
    this.init = function() {
        arikaim.ui.tab();    
    };
}

var productsControlPanel = new ProductsControlPanel();

arikaim.component.onLoaded(function() {
    productsControlPanel.init();
});