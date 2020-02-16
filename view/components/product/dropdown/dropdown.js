$(document).ready(function() {  
    $('.product-dropdown').dropdown({
        apiSettings: {     
            on: 'now',      
            url: arikaim.getBaseUrl() + '/api/products/product/list/dropdown/{query}',   
            cache: false        
        },
        filterRemoteData: false         
    });
});