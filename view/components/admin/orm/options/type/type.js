/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function OptionsType() {
    var self = this;

    this.add = function(formId, onSuccess, onError) {       
        return arikaim.post('/core/api/orm/options/type/add',formId,onSuccess,onError);          
    };
    
    this.delete = function(model, extension, id, onSuccess, onError) {
        var data = {
            model: model,
            extension: extension,
            id: id
        };       

        return arikaim.put('/core/api/orm/options/type/delete',data,onSuccess,onError);          
    };

    this.update = function(formId, onSuccess, onError) {       
        return arikaim.put('/core/api/orm/options/type/update',formId,onSuccess,onError);          
    };
}

var optionsType = new OptionsType();
