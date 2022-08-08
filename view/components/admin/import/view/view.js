/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
*/
'use strict';

function ImportProductsView() {
    var self = this;

    this.init = function() {
    
        $('.drivers-dropdown').dropdown({
            onChange: function(selected) {
            }
        });
    };

    this.initRows = function() {    
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