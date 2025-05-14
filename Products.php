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
        // Api      
        $this->addApiRoute('GET','/api/products/list/{category}','ProductsApi','getList');   
        $this->addApiRoute('GET','/api/products/product/list/dropdown/{data_field}/{user}/[{query}]','ProductsApi','getDropdownList');   
        $this->addApiRoute('GET','/api/products/product/details/{uuid}','ProductsApi','productDetails');  
        $this->addApiRoute('GET','/api/products/price/{uuid}','ProductsApi','getPrice');   
        $this->addApiRoute('POST','/api/products/product/add','ProductsApi','add',['session','token']);  
        $this->addApiRoute('PUT','/api/products/product/update','ProductsApi','update',['session','token']);  
        $this->addApiRoute('DELETE','/api/products/product/delete/{uuid}','ProductsApi','deleteUserProduct',['session','token']);  
        $this->addApiRoute('PUT','/api/products/status','ProductsApi','setStatus','session');
        $this->addApiRoute('PUT','/api/products/restore/{uuid}','ProductsApi','restore','session');     
        $this->addApiRoute('PUT','/api/products/update/description','ProductsApi','updateDescription','session');
        $this->addApiRoute('PUT','/api/products/update/meta','ProductsApi','updateMetaTags','session'); 
        // Price 
        $this->addApiRoute('PUT','/api/products/price/update','ProductsApi','updatePrice','session');
        // Options
        $this->addApiRoute('PUT','/api/products/options/update','ProductsApi','updateOptions','session');
        // External Id
        $this->addApiRoute('POST','/api/products/external/id','ProductsApi','addExternalId','session');    
        $this->addApiRoute('DELETE','/api/products/external/id/{uuid}','ProductsApi','deleteExternalId','session');  
        // Product type
        $this->addApiRoute('POST','/api/admin/products/type/add','ProductTypeControlPanel','add','session');  
        $this->addApiRoute('PUT','/api/admin/products/type/update','ProductTypeControlPanel','update','session');
        $this->addApiRoute('DELETE','/api/admin/products/type/delete/{uuid}','ProductTypeControlPanel','delete','session');
        // Events
        $this->registerEvent('product.add','Add product');  
        $this->registerEvent('product.update','Update product');  
        $this->registerEvent('product.delete','Delete product');  
        // Relation map 
        $this->addRelationMap('product','Products');
        // Service
        $this->registerService('ProductsService');  
        // Add Permissions
        $this->addPermission('Products','Manage products','Create, edit and delete products');
    }   

    /**
     * Create db tables
     *
     * @return void
     */
    public function dbInstall(): void
    {
        // Create db tables        
        $this->createDbTable('ProductType');
        $this->createDbTable('Products');
        $this->createDbTable('ProductId');   
        $this->createDbTable('ProductTranslations');
        $this->createDbTable('ProductRelations');
        // Product options
        $this->createDbTable('ProductOptionType');
        $this->createDbTable('ProductOptions');     
        $this->createDbTable('ProductPriceList'); 
    }

    /**
     * Post install actions
     *
     * @return void
     */
    public function postInstall()
    {      
        ProductType::importOptionsType('options-type.json','products');
        ProductType::import('product-types.json','products');
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
