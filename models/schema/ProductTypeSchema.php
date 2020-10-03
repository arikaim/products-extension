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

/**
 * Product type db table
 */
class ProductTypeSchema extends Schema  
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
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {              
        if ($this->hasColumn('readonly') == false) {
            $table->integer('readonly')->nullable(true);
        } 
    }

    /**
     * Insert or update rows in table
     *
     * @param Seed $seed
     * @return void
     */
    public function seeds($seed)
    {  
        $seed->create(['slug' => 'software'],[
                'uuid'   => Uuid::create(),
                'title'  => 'Software',               
                'status' => 1
            ]
        ); 

        $seed->create(['slug' => 'subscription'],[
            'uuid'      => Uuid::create(),
            'readonly'  => 1,
            'title'     => 'Subscription',               
            'status'    => 1
            ]
        ); 
    }
}
