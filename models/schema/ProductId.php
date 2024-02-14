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

/**
 * External product id mapping db table
 */
class ProductId extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'product_id';

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {
        // fields
        $table->prototype('id');
        $table->prototype('uuid');
        $table->relation('product_id','products');

        $table->string('external_id')->nullable(false);      
        $table->string('api_driver')->nullable(false);  
        $table->metaTags();      
        // index
        $table->unique(['external_id','api_driver']);         
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {                  
        if ($this->hasColumn('meta_title') == false) {
            $table->metaTags();      
        } 
    }
}
