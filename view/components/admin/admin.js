/**
 *  Arikaim  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */

function StoreControlPanel() {

    this.init = function() {
        arikaim.ui.tab();
    };
}

var store = new StoreControlPanel();

arikaim.page.onReady(function() {
    store.init();
});