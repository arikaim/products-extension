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

use Arikaim\Extensions\Products\Models\ProductType;
use Arikaim\Extensions\Products\Models\ProductOptions;
use Arikaim\Extensions\Products\Models\ProductPriceList;
use Arikaim\Extensions\Products\Models\ProductTranslations;
use Arikaim\Core\Models\Users;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\Position;
use Arikaim\Core\Db\Traits\SoftDelete;
use Arikaim\Core\Db\Traits\Slug;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\DateUpdated;
use Arikaim\Core\Db\Traits\Options\OptionsRelation;
use Arikaim\Core\Db\Traits\Price\PriceRelation;
use Arikaim\Extensions\Category\Models\Traits\CategoryRelations;
use Arikaim\Core\Db\Traits\Translations;

/**
 * Products model class
 */
class Products extends Model  
{
    use Uuid,
        Status,
        Find,
        DateCreated,
        DateUpdated,
        SoftDelete,
        Slug,
        OptionsRelation,
        PriceRelation,
        CategoryRelations,
        Translations,
        Position;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Visible columns
     *
     * @var array
     */
    protected $visible = [
        'type',
        'position',
        'uuid',           
        'date_created',      
        'slug',
        'title',  
        'price',
        'key',
        'options_list',
        'price_list',
        'is_free'            
    ];

    /**
     * Append custom attributes
     *
     * @var array
     */
    protected $appends = [
        'options_list',
        'price_list',
        'is_free'
    ];

    /**
     * Include relations
     *
     * @var array
     */
    protected $with = [       
        'type',
        'price',
        'options',
        'categories'
    ];
    
    /**
     * Fillable columns
     *
     * @var array
    */
    protected $fillable = [
        'position',
        'uuid',     
        'status',
        'date_created',
        'date_deleted',
        'slug',
        'title',       
        'type_id',
        'user_id'
    ];
   
    /**
     * Db column names which are translated to other languages
     *
     * @var array
     */
    protected $translatedAttributes = [
        'title'       
    ];

    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * Translation column ref
     *
     * @var string
     */
    protected $translationReference = 'product_id';

    /**
     * Translatin model class
     *
     * @var string
     */
    protected $translationModelClass = ProductTranslations::class;

    /**
     * Options class name
     *
     * @var string
     */
    protected $optionsClass = ProductOptions::class;

    /**
     * Price list class
     *
     * @var string
     */
    protected $priceListClass = ProductPriceList::class;

    /**
     * Get options type name
     *
     * @return string|null
     */
    public function getOptionsType()
    {
        return $this->type->slug;
    }

    /**
     * Product type relation
     *
     * @return mixed
     */
    public function type()
    {
        return $this->belongsTo(ProductType::class,'type_id');
    }

    /**
     * User relation
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(Users::class,'user_id');
    }

    /**
     * Return true if product exists
     *
     * @param string $title
     * @return boolean
     */
    public function hasProduct($title)
    {
        $model = $this->findByColumn($title,'title');

        return \is_object($model);
    }    

    /**
     * Get type query
     *
     * @param Builder $query
     * @param int|null $typeId
     * @return Builder
     */
    public function scopeTypeQuery($query, $typeId = null)
    {
        return (empty($typeId) == false) ? $query->where('type_id','=',$typeId) : $query;
    }
}
