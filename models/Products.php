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
use Arikaim\Extensions\Products\Models\ProductRelations;

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
use Arikaim\Core\Db\Traits\MetaTags;

use Arikaim\Extensions\Category\Models\Traits\CategoryRelations;
use Arikaim\Extensions\Image\Models\Traits\ImageRelation;
use Arikaim\Core\Db\Traits\Translations;

/**
 * Products model class
 */
class Products extends Model  
{
    use Uuid,
        Status,
        Find,
        MetaTags,
        DateCreated,
        DateUpdated,
        SoftDelete,
        Slug,
        OptionsRelation,
        PriceRelation,
        CategoryRelations,
        ImageRelation,
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
        'is_free'
    ];

    /**
     * Include relations
     *
     * @var array
     */
    protected $with = [       
        'type',
        'prices',
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
        'title',
        'description',    
        'description_summary',
        'meta_title',
        'meta_description',
        'meta_keywords'    
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
        $this->prices()->delete();

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
    public function findProduct($key, ?int $userId = null): ?object
    {
        $query = $this->where('slug','=',$key);
        $query = (empty($userId) == true) ? $query->whereNull('user_id') : $query->where('user_id','=',$userId);
        $model = $query->first();

        return ($model != null) ? $model : $this->findById($key);         
    }

    /**
     * Get product relations
     *
     * @return Relatins|null
     */
    public function relations()
    {
        return $this->hasMany(ProductRelations::class,'product_id')->without('product');
    }

    /**
     * Find product relations
     *
     * @param string       $type
     * @param integer|null $id
     * @return object|null
     */
    public function findRelation(string $type, ?int $id = null): ?object 
    {
        $query = $this->relations()->where('relation_type','=',$type);
        $query = (empty($id) == false) ? $query->where('relation_id','=',$id) : $query;

        return $query->first();
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
     * @return Relation|null
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
       
        if ($query->first() !== null) {
            return true;
        }

        // try with slug 
        return ($this->findProduct(Utils::slug($title),$userId) != null);
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
