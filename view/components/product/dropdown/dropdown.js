'use strict';

arikaim.component.onLoaded(function() {
    var dataField = $('.product-dropdown').attr('data-field');

    $('.product-dropdown').dropdown({
        apiSettings: {     
            on: 'now',      
            url: arikaim.getBaseUrl() + '/api/products/product/list/dropdown/' + dataField + '/{query}',   
            cache: false        
        },
        filterRemoteData: false,
        allowAdditions: true,
        onChange: function(value) {
            console.log(value);
        }         
    });
});