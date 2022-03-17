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

use Arikaim\Extensions\Products\Models\ProductOptionType;
use Arikaim\Extensions\Products\Models\ProductOptionsList;
use Arikaim\Extensions\Currency\Models\Currency;
use Arikaim\Extensions\Products\Models\Products;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Price\PriceList;
use Arikaim\Core\Db\Traits\Options\OptionsListDefinition;

/**
 * Price list model class
 */
class ProductPriceList extends Model  
{
    use Uuid,       
        PriceList,
        OptionsListDefinition,
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
        'currency_id',  
        'product_id',          
        'price',
        'key',
        'uuid'          
    ];

    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Price type model class
     *
     * @var string
     */
    protected $priceTypeClass = ProductOptionType::class;
    
    /**
     * Price list definition model class
     *
     * @var string
     */
    protected $priceListDefinitionClass = ProductOptionsList::class;

    /**
     * Currency class
     *
     * @var string
     */
    protected $currencyClass = Currency::class;

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
