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

/**
 * Product id model class
 */
class ProductId extends Model  
{
    use Uuid,     
        Find;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'product_id';

    /**
     * Fillable columns
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'external_id',
        'api_driver',
        'product_id'      
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * Return true if id ref exist
     *
     * @param string $externalId
     * @param string $apiDriver
     * @return boolean
     */
    public function hasId(string $externalId, string $apiDriver): bool
    {
        $model = $this->findProduct($externalId,$apiDriver)->first();

        return ($model != null);
    }

    /**
     * Find product query
     *
     * @param Builder $query
     * @param string $externalId
     * @param string $apiDriver
     * @return Builder
     */
    public function scopeFindProduct($query, string $externalId, string $apiDriver)
    {
        return $query->where('external_id','=',$externalId)->where('api_driver','=',$apiDriver);
    } 

    /**
     * Add id ref
     *
     * @param integer $productId
     * @param string $externalId
     * @param string $apiDriver
     * @return boolean
     */
    public function addId(int $productId, string $externalId, string $apiDriver): bool
    {       
        if ($this->hasId($externalId,$apiDriver) == true) {
            return false;
        }

        $model = $this->create([
            'external_id' => $externalId,
            'api_driver'  => $apiDriver,
            'product_id'  => $productId
        ]);

        return ($model != null);
    }
}
