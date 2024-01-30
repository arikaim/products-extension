'use strict';

arikaim.component.onLoaded(function(component) {

    component.init = function() {
        var dataField = component.get('data-field').trim();
        $('#' + component.getId()).dropdown({
            apiSettings: {     
                on: 'now',      
                url: arikaim.getBaseUrl() + '/api/products/product/list/dropdown/' + dataField + '/{query}',   
                cache: false        
            },
            filterRemoteData: false,
            onChange: function(value) {
                component.set('selected',value);
            }         
        });
    }
   
    component.init();

    return component;
});