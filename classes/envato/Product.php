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

use Arikaim\Extensions\Products\Classes\ProductInterface;
use Arikaim\Extensions\Products\Classes\AbstractProduct;

/**
 * Envato Sales order
*/
class Product extends AbstractProduct implements ProductInterface
{
    /**
     * Create product
     *
     * @param array $data
     * @return ProductInterface|null 
     */
    public function create(array $data)
    {
        $product = new Self();

        $product->id = $data['id'] ?? null;
        $product->price = $data['price_cents'] ?? null;
        $product->currency = 'USD';
        $product->title = $data['name'] ?? null; 
        $product->description = $data['description'] ?? null; 

        if ((empty($product->id) ==true) || (empty($product->title) == true) || (\is_null($product->price) == true)) {
            return null;
        }

        $product->price = (float)($product->price / 100);      
       
        return $product;
    }
}
