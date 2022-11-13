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
use Arikaim\Extensions\Products\Models\ProductOptionsList;
use Arikaim\Extensions\Products\Models\ProductOptionType;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\Slug;
use Arikaim\Core\Db\Traits\Find;

/**
 * Product type model class
 */
class ProductType extends Model  
{
    use Uuid,
        Slug,
        Status,
        Find;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'product_type';

    /**
     * Fillable columns
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'status',
        'slug',
        'title',
        'readonly',
        'description'
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Visible columns
     *
     * @var array
     */
    protected $visible = [             
        'slug',
        'title',              
    ];

    /**
     * Producucs relation
     *
     * @return Relation|null
     */
    public function products()
    {
        return $this->hasMany(Products::class,'type_id');
    }

    /**
     * Return true if product type has products
     *
     * @return boolean
     */
    public function hasProducts(): bool
    {
        $count = $this->products()->count();

        return ($count > 0);
    }

    /**
     * Editable product type scope
     *
     * @param bool $readonly
     * @return Builder
     */
    public function scopeEditable($query, bool $readonly = false)
    {
        return ($readonly == false) ? $query->whereNull('readonly')->orWhere('readonly','!=',1) : $query->where('readonly','=',1);
    }    

    /**
     * Gte options list
     *
     * @return Collection
     */
    public function getOptionsType()
    {
        $optionsList = new ProductOptionsList();

        return $optionsList->where('type_name','=',$this->slug)->get();
    }

    /**
     * Get option type
     *
     * @param string $key
     * @return Model|null
     */
    public function getOptionType(string $key): ?object
    {
        $optionType = new ProductOptionType();

        return $optionType->where('key','=',$key)->first();
    }
}
