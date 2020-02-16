<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Models\Schema;

use Arikaim\Core\Db\Schema;
use Arikaim\Core\Utils\Uuid;
use Arikaim\Core\Extension\Extension;

/**
 * Product options list db table
 */
class ProductOptionsListSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = "product_options_list";

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {            
        $table->tableOptionsList(function($table) {

        });
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {              
    }

    /**
     * Insert or update rows in table
     *
     * @param Seed $seed
     * @return void
     */
    public function seeds($seed)
    {       
        $items = Extension::loadJsonConfigFile('product-options-list.json','store');
        
        $seed->createFromArray(['key','type_name'],$items,function($item) {
            $item['uuid'] = Uuid::create();
        
            return $item;
        });
    }
}
