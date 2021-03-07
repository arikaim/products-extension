'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit("#meta_tags_form",function() {     
        var language = $('#choose_language').dropdown('get value');
        $('#language').val(language);

        return arikaim.put('/api/products/admin/product/update/meta','#meta_tags_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});