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
use Arikaim\Extensions\Products\Models\ProductOptionType;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\Slug;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\OptionsAttribute;

/**
 * Product type model class
 */
class ProductType extends Model  
{
    use Uuid,
        Slug,
        Status,
        OptionsAttribute,
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
        'id',
        'uuid',
        'status',
        'slug',
        'title',
        'readonly',
        'description',
        'include_in_sitemap',
        'options'
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

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
        return ($this->products()->count() > 0);
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
     * Get options list
     *
     * @return array
     */
    public function getOptionsType(?string $type = null, bool $exclude = false): array
    {
        $optionType = new ProductOptionType();
        $result = [];

        foreach ($this->options as $key) {
            $option = $optionType->where('key','=',$key)->first();
            
            if ($option == null) continue;
            
            $option = $option->toArray();
            $option['name'] = $option['key'];

            if (empty($type) == false) {
                $typeId = $optionType->getOptionTypeId($type);
                if ($exclude == false) {
                    if ($typeId == $option['type']) {
                        $result[$key] = $option;
                    }
                } else {
                    if ($typeId != $option['type']) {
                        $result[$key] = $option;
                    }
                }
            } else {   
                $result[$key] = $option;
            }
        }

        return $result;
    }
}
