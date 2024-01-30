/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ProductOptionsView() {
 
    this.init = function() {
        this.loadMessages('products::admin.messages');
        this.initRows();  
    };

    this.initRows = function() {       
        arikaim.ui.loadComponentButton('.option-action-button');      
    };
};

var productOptionsView = createObject(ProductOptionsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    productOptionsView.init();
}); 