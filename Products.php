<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products;

use Arikaim\Core\Extension\Extension;

/**
 * Extension class
 */
class Products extends Extension
{
    /**
     * Install extension
     *
     * @return void
     */
    public function install()
    {
        // Control Panel Routes
        // product
        $this->addApiRoute('POST','/api/products/admin/product/add','ProductControlPanel','add','session');  
        $this->addApiRoute('PUT','/api/products/admin/product/update','ProductControlPanel','update','session');
        $this->addApiRoute('PUT','/api/products/admin/product/status','ProductControlPanel','setStatus','session');
        $this->addApiRoute('DELETE','/api/products/admin/product/delete/{uuid}','ProductControlPanel','softDelete','session');
        $this->addApiRoute('PUT','/api/products/admin/product/restore/{uuid}','ProductControlPanel','restore','session');
        $this->addApiRoute('PUT','/api/products/admin/product/create/options','ProductControlPanel','createOptions','session');       
        $this->addApiRoute('PUT','/api/products/admin/product/update/meta','ProductControlPanel','updateMetaTags','session');
        // product type
        $this->addApiRoute('POST','/api/products/admin/product/type/add','ProductTypeControlPanel','add','session');  
        $this->addApiRoute('PUT','/api/products/admin/product/type/update','ProductTypeControlPanel','update','session');
        $this->addApiRoute('DELETE','/api/products/admin/product/type/delete/{uuid}','ProductTypeControlPanel','delete','session');

        // price 
        $this->addApiRoute('PUT','/api/products/admin/product/price/update','PriceListControlPanel','update','session');
        $this->addApiRoute('PUT','/api/products/admin/product/price/create','PriceListControlPanel','createPriceList','session');       
        
        // Api
        // products 
        $this->addApiRoute('GET','/api/products/product/list/dropdown/[{query}]','ProductsApi','getDropdownList');   
        $this->addApiRoute('GET','/api/products/product/list[/{params:.*}]','ProductsApi','getList');   
        
        // Events
        $this->registerEvent('product.add','Add product');  
        $this->registerEvent('product.update','Update product');  
        // Relation map 
        $this->addRelationMap('product','Products');
        // Create db tables        
        $this->createDbTable('ProductTypeSchema');
        $this->createDbTable('ProductsSchema');
        $this->createDbTable('ProductTranslationsSchema');
        // Product options
        $this->createDbTable('ProductOptionTypeSchema');
        $this->createDbTable('ProductOptionsListSchema');
        $this->createDbTable('ProductOptionsSchema');     
        $this->createDbTable('ProductPriceListSchema');                
    }   

    /**
     * UnInstall extension
     *
     * @return void
     */
    public function unInstall()
    {  
    }
}
