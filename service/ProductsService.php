<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Service;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Service\Service;
use Arikaim\Core\Service\ServiceInterface;

/**
 * Prodcuts service class
*/
class ProductsService extends Service implements ServiceInterface
{
    /**
     * Boot service
     *
     * @return void
     */
    public function boot()
    {
        $this->setServiceName('products');
    }

    /**
     * Find product
     *
     * @param mixed       $key
     * @param integer|null $userId
     * @return object|null
     */
    public function find($key, ?int $userId = null): ?object
    {
        return Model::Products('products')->findProduct($key,$userId);
    }

    /**
     * Find product by type
     *
     * @param string $type
     * @param string|null $productSlug
     * @return object|null
     */
    public function findProductByType(string $type, ?string $productSlug = null): ?object
    {
        $type = Model::ProductType('products')->findBySlug($type);
        if ($type == null) {
            return null;
        }
        if (empty($productSlug) == true) {
            return $type->products()->first();
        }
        
        return $type->products()->where('slug','=',$productSlug)->first();
    }
}
