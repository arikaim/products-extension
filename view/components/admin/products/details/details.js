'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.button('.close-button',function(element) {
        $('#product_details').fadeOut(400);
    });
});