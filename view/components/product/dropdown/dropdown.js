'use strict';

arikaim.component.onLoaded(function(component) {

    component.init = function() {
        var dataField = this.get('data-field');

        $('#' + this.getId()).dropdown({
            apiSettings: {     
                on: 'now',      
                url: arikaim.getBaseUrl() + '/api/products/product/list/dropdown/' + dataField + '/{query}',   
                cache: false        
            },
            filterRemoteData: false,
            allowAdditions: true,
            onChange: function(value) {
                component.set('selected',value);
            }         
        });
    }
   
    component.init();

    return component;
});