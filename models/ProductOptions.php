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

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Options\Options;

/**
 * Product Options model class
 */
class ProductOptions extends Model  
{
    use Uuid,       
        Options,
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'product_options';

    /**
     * Visible columns
     *
     * @var array
     */
    protected $visible = [
        'key',
        'value'                
    ];

    /**
     * Fillable columns
     *
     * @var array
     */
    protected $fillable = [
        'reference_id',       
        'value',
        'key',
        'uuid',
        'type_id'       
    ];

    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Option type model class 
     *
     * @var string
     */
    protected $optionTypeClass = ProductOptionType::class;

    /**
     * Options definition model class
     *
     * @var string
     */
    protected $optionsDefinitionClass = ProductOptionsList::class;
}
