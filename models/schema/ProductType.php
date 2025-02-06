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
 * Product type db table
 */
class ProductType extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'product_type';

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
        $table->slug();
        $table->string('title')->nullable(false);
        $table->status();
        $table->integer('readonly')->nullable(true);
        $table->string('description')->nullable(true);    
        $table->integer('include_in_sitemap')->nullable(false)->default(1);
        $table->options();       

    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {        
        if ($this->hasColumn('include_in_sitemap') == false) {
            $table->integer('include_in_sitemap')->nullable(false)->default(1);
        } 

        if ($this->hasColumn('readonly') == false) {
            $table->integer('readonly')->nullable(true);
        } 
        if ($this->hasColumn('options') == false) {
            $table->options();    
        } 
    }
}
