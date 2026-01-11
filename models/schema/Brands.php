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
 * Brands db table
 */
class Brands extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'brands';

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
        $table->status();
        $table->string('title')->nullable(false);
        $table->slug(false);      
        $table->string('description')->nullable(true);
        $table->relation('image_id','image',true); 
        $table->string('icon')->nullable(true);
        $table->userId();
        $table->options();   
        $table->dateCreated();
        // unique
        $table->unique(['slug','user_id']); 
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
}
