'use strict';

arikaim.component.onLoaded(function(component) {

    component.init = function() {
        var dataField = component.get('data-field').trim();
        var user = component.get('user').trim();
        if (isEmpty(user) == true) {
            user = '0';
        }
        $('#' + component.getId()).dropdown({
            apiSettings: {     
                on: 'now',      
                url: arikaim.getBaseUrl() + '/api/products/product/list/dropdown/' + dataField + '/' + user + '/{query}',   
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