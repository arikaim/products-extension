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
class Products extends Schema  
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
        $table->slug(false);
        $table->string('title')->nullable(false);
        $table->string('sku')->nullable(true);
        $table->text('description')->nullable(true);
        $table->text('description_summary')->nullable(true);
        $table->userId(true);
        $table->status();
        $table->position();      
        $table->relation('type_id','product_type'); 
        $table->relation('image_id','image',true); 
        $table->metaTags();     
        $table->dateCreated();
        $table->dateUpdated();
        $table->dateDeleted();
        // index
        $table->unique(['title','user_id']);
        $table->unique(['slug','user_id']);
        $table->unique(['sku','user_id']);
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

        if ($this->hasColumn('description') == false) {
            $table->text('description')->nullable(true);
        }
        if ($this->hasColumn('description_summary') == false) {
            $table->text('description_summary')->nullable(true);
        }
        if ($this->hasColumn('image_id') == false) {
            $table->relation('image_id','image',true); 
        }
        
        if ($this->hasColumn('sku') == false) {
            $table->string('sku')->nullable(true);
            $table->unique(['sku','user_id']);
        }
    }
}
