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
 * Products db table
 */
class ProductsSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'products';

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {
        // columns
        $table->prototype('id');
        $table->prototype('uuid');
        $table->slug();
        $table->string('title')->nullable(false);
        $table->text('description')->nullable(true);
        $table->userId();
        $table->status();
        $table->position();      
        $table->relation('type_id','product_type'); 
        $table->dateCreated();
        $table->dateUpdated();
        $table->dateDeleted();
        // index
        $table->unique(['title']);
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {                   
        if ($table->hasColumn('description') == false) {
            $table->text('description')->nullable(true);
        }
    }
}
