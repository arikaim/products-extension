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
     * Save product
     * @param string $title
     * @param string $typeSlug
     * @param mixed $userId
     * @return bool
     */
    public function saveProduct(string $title, string $typeSlug, ?int $userId = null): bool
    {
        $model = Model::Products('products');
        if ($model->hasProduct($title,$userId) == true) {
            return true;
        }

        $type = Model::ProductType('products')->findBySlug($typeSlug);
        if ($type == null) {
            return false;
        }

        $created = $model->create([
            'type_id' => $type->id,
            'title'   => $title,
            'user_id' => $userId,
        ]);

        return ($created !== null);
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
