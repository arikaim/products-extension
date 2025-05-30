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

use Arikaim\Extensions\Currency\Models\Traits\CurrencyRelation;
use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Price\PriceList;

/**
 * Price list model class
 */
class ProductPriceList extends Model  
{
    use Uuid,     
        CurrencyRelation,  
        PriceList,
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'product_price_list';

    /**
     * Fillable columns
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'currency_id',  
        'product_id',          
        'price',
        'key',
        'uuid'          
    ];

    /**
     * Add relation
     *
     * @var array
     */
    protected $with = [
        'currency'
    ];

    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get free products
     *
     * @param Builder $query
     * @return Builder $query
     */
    public function scopeFreeProducts($query)
    {
        return $query->where('price','=',0)->orWhereNull('price')->distinct();
    } 

    /**
     * Product relation
     *
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
}
