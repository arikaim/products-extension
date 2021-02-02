<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Classes\Envato;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Arikaim;
use Arikaim\Extensions\Products\Classes\Envato\Product;
use Arikaim\Extensions\Products\Classes\ProductInterface;
use Arikaim\Extensions\Products\Classes\ExternalProductInterface;

/**
 * External product
*/
class ExternalProduct implements ExternalProductInterface
{
    /**
     * Import product
     *
     * @param string $id
     * @param string $driverName
     * @return Model|null
     */
    public function import(string $id, string $driverName)
    {
        return null;
    } 
    
    /**
     * Get product details
     *
     * @param integer $id
     * @param string $driverName
     * @return array|null
    */
    public function getDetails(int $id, string $driverName): ?array
    {
        return [];
    }

    /**
     * Create product
     *
     * @param string $id
     * @param string $driverName
     * @return ProductInterface|null
     */
    public function create(string $id, string $driverName)
    {
        $driver = Arikaim::get('driver')->create($driverName);
        if ($driver === false) {
            return null;
        }
        $details = $this->getDetails($id,$driverName);
        if (\is_null($details) == true) {
            return null;
        }
        
    }
    
    /**
     * Get product list
     *
     * @param integer $page
     * @param string $driverName
     * @return array|null
    */
    public function getList(int $page = 1, string $driverName): ?array
    {
        $driver = Arikaim::get('driver')->create($driverName);
        if ($driver === false) {
            return null;
        }
        $apiFunc = $driver->createApiFunction('ItemsSearch',[
            'page'     => $page,
            'username' => $driver->getDriverConfigVar('username')
        ]);

        $result = $apiFunc->call()->toArray();
        $products = [];
        foreach($result['matches'] as $item) {
            $product = Product::createFromArray($item);
            if (empty($product) == false) {
                $products[] = $product;
            }
        }

        return $products;
    }
}
