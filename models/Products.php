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
use Arikaim\Extensions\Products\Models\ProductId;

use Arikaim\Core\Utils\Utils;
use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\UserRelation;
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
        UserRelation,
        Translations,
        Position;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products';

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
        'date_updated',
        'date_deleted',
        'slug',
        'title',   
        'description',    
        'description_summary',
        'image_id',
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
     * Hard delete product model
     *
     * @return boolean
     */
    public function deleteProduct(): bool
    {
        // delete external ref
        $this->external()->delete();
        // delete options
        $this->options()->where('reference_id','=',$this->id)->delete();
        // delete translations
        $this->translations()->delete();
        // delete price list 
        $this->price()->delete();

        // delete prodcut
        $result = $this->delete();
        return ($result !== false);
    }

    /**
     * Find product by slug, uuid
     *
     * @param string|int $key
     * @param int|null $userId 
     * @return Model|null
     */
    public function findProduct($key, ?int $userId = null)
    {
        $query = $this->where('slug','=',$key);
        $query = (empty($userId) == true) ? $query->whereNull('user_id') : $query->where('user_id','=',$userId);
        $model = $query->first();

        return (\is_object($model) == true) ? $model : $this->findById($key);         
    }

    /**
     * Image relation
     *
     * @return Relation|null
     */
    public function image()
    {
        return $this->belongsTo('Arikaim\\Extensions\\Image\\Models\\Image','image_id');
    }
    
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
     * Product id relations
     *
     * @return mixed
     */
    public function external()
    {
        return $this->hasMany(ProductId::class,'product_id');
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
     * Return true if product exists
     *
     * @param string $title
     * @param int|null $userId
     * @return boolean
     */
    public function hasProduct(string $title, ?int $userId = null): bool
    {
        $title = \trim($title);
        $query = $this->where('title','=',$title);
        $query = (empty($userId) == true) ? $query->whereNull('user_id') : $query->where('user_id','=',$userId);        
        $model = $query->first();

        if (\is_object($model) == true) {
            return true;
        }
        // try with slug 
        $slug = Utils::slug($title);
        $model = $this->findProduct($slug,$userId);

        return \is_object($model);
    }    

    /**
     * Get type query
     *
     * @param Builder $query
     * @param int|null $typeId
     * @return Builder
     */
    public function scopeTypeQuery($query, ?int $typeId = null)
    {
        return (empty($typeId) == false) ? $query->where('type_id','=',$typeId) : $query;
    }

    /**
     * Get user products query
     *
     * @param Builder $query
     * @param int|null $typeId
     * @return Builder
     */
    public function scopeUserProductsQuery($query, ?int $userid)
    {
        return $query->where('user_id','=',$userid);
    }
}
