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
    protected $table = "product_price_list";

    /**
     * Visible columns
     *
     * @var array
     */
    protected $visible = [        
        'key',
        'price'        
    ];

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
}
