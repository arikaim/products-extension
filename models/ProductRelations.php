<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Extensions\Products\Models\Products;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\PolymorphicRelations;

/**
 * Product relations class
 */
class ProductRelations extends Model  
{
    use 
        Uuid,
        PolymorphicRelations,
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'product_relations';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'relation_id',
        'relation_type'       
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Relation model class
     *
     * @var string
     */
    protected $relationModelClass = Products::class;

    /**
     * Relation column name
     *
     * @var string
     */
    protected $relationColumnName = 'product_id';

    /**
     * Tag model relation
     *
     * @return Relation
     */
    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
}
