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

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Options\OptionType;

/**
 * Product Option Type model class
 */
class ProductOptionType extends Model  
{
    use Uuid,       
        OptionType,
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'product_option_type';   

    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
}
