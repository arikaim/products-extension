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
use Arikaim\Core\Db\Traits\MetaTags;

/**
 * Product language translations model class
 */
class ProductTranslations extends Model  
{
    use 
        Uuid,      
        MetaTags,
        Find;
       
    /**
     * Db table name
     *
     * @var string
     */
    protected $table = 'product_translations';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'title',        
        'meta_title',
        'meta_description',
        'meta_keywords',        
        'language'
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Game relation
     *
     * @return mixed
     */
    public function product()
    {
        return $this->hasOne(Products::class,'id','product_id'); 
    }
}
