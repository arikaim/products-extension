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
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\Slug;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\UserRelation;
use Arikaim\Core\Db\Traits\OptionsAttribute;
use Arikaim\Extensions\Image\Models\Traits\ImageRelation;

/**
 * Product brands model class
 */
class Brands extends Model  
{
    use Uuid,
        Slug,
        DateCreated,
        UserRelation,
        ImageRelation,
        Status,
        OptionsAttribute,
        Find;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'brands';

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
        'icon',
        'image_id',
        'user_id',
        'description',
        'date_created',
        'options'
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Find brand
     * @param string $title
     * @param int|null $userId
     */
    public function findBrand(string $title, ?int $userId = null): ?object
    {      
        return $this
            ->userQuery($userId)
            ->where('title','=',$title)
            ->first();
    }

    /**
     * Return true if brand exist
     * @param string $title
     * @param mixed $userId
     * @return bool
     */
    public function hasBrand(string $title, ?int $userId = null): bool
    {
        return ($this->findBrand($title,$userId) != null);
    }

    /**
     * Products relation
     *
     * @return object|null
     */
    public function products()
    {
        return $this->hasMany(Products::class,'brand_id');
    }

    /**
     * Return true if brand has products
     *
     * @return boolean
     */
    public function hasProducts(): bool
    {
        return ($this->products()->count() > 0);
    }
}
