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
use Arikaim\Extensions\Products\Classes\ProductType;

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
        $this->addApiRoute('POST','/api/admin/products/add','ProductControlPanel','add','session');  
        $this->addApiRoute('PUT','/api/admin/products/update','ProductControlPanel','update','session');
        $this->addApiRoute('PUT','/api/admin/products/status','ProductControlPanel','setStatus','session');
        $this->addApiRoute('DELETE','/api/admin/products/delete/{uuid}','ProductControlPanel','softDelete','session');
        $this->addApiRoute('PUT','/api/admin/products/restore/{uuid}','ProductControlPanel','restore','session');
        $this->addApiRoute('PUT','/api/admin/products/create/options','ProductControlPanel','createOptions','session');       
        $this->addApiRoute('PUT','/api/admin/products/update/meta','ProductControlPanel','updateMetaTags','session');
        // product type
        $this->addApiRoute('POST','/api/admin/products/type/add','ProductTypeControlPanel','add','session');  
        $this->addApiRoute('PUT','/api/admin/products/type/update','ProductTypeControlPanel','update','session');
        $this->addApiRoute('DELETE','/api/admin/products/type/delete/{uuid}','ProductTypeControlPanel','delete','session');
        // price 
        $this->addApiRoute('PUT','/api/admin/products/price/update','PriceListControlPanel','update','session');
        $this->addApiRoute('PUT','/api/admin/products/price/create','PriceListControlPanel','createPriceList','session');       
        // external Id
        $this->addApiRoute('POST','/api/admin/products/external/id','ProductControlPanel','addExternalId','session');    
        $this->addApiRoute('DELETE','/api/admin/products/external/id/{uuid}','ProductControlPanel','deleteExternalId','session');  

        // Api      
        $this->addApiRoute('GET','/api/products/product/list/dropdown/[{query}]','ProductsApi','getDropdownList');   
        $this->addApiRoute('GET','/api/products/product/list[/{params:.*}]','ProductsApi','getList');   
        $this->addApiRoute('GET','/api/products/product/details/{uuid}','ProductsApi','getProductDetails');   

        // Events
        $this->registerEvent('product.add','Add product');  
        $this->registerEvent('product.update','Update product');  
        // Relation map 
        $this->addRelationMap('product','Products');
        // Create db tables        
        $this->createDbTable('ProductTypeSchema');
        $this->createDbTable('ProductsSchema');
        $this->createDbTable('ProductIdSchema');   
        $this->createDbTable('ProductTranslationsSchema');
        $this->createDbTable('ProductRelationsSchema');
        // Product options
        $this->createDbTable('ProductOptionTypeSchema');
        $this->createDbTable('ProductOptionsListSchema');
        $this->createDbTable('ProductOptionsSchema');     
        $this->createDbTable('ProductPriceListSchema');   
    }   

    /**
     * Post install actions
     *
     * @return void
     */
    public function postInstall()
    {
        ProductType::importOptionsType('options-type.json','products');
        ProductType::import('standard-product.json','products');
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
