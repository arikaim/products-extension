<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Controllers\Traits;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Paginator\Paginator;

/**
 * Products list trait
*/
trait Products 
{
    /**
     * Get products list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getProductsList($request, $response, $data)
    {
        $products = Model::Products('products')->getActive();
       
        $queryParams = $this->resolveRequestParams($request,['category_slug','page']);
        $page = $queryParams['page'] ?? 1;
        $categorySlug = $this->getParam('category_slug',$queryParams['category_slug']);
        
        $products = Model::Category('category',function($model) use($categorySlug,$products) {                
            return $model->relationsQuery($products,$categorySlug);           
        });

        return Paginator::create($products,$page);
    }

    /**
     * Get product price
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getPriceController($request, $response, $data)
    {
        $data
            ->addRule('text:required','uuid','Not valid product Id')
            ->validate(true);

        $uuid = $data->get('uuid');
        $currencyId = $data->get('currency_id',$this->get('currency')->getDefaultCurrencyId());
        
        $product = Model::Products('products')->findById($uuid);
        if ($product == null) {
            $this->error('errors.id','Not valid product id');
            return false;
        }

        $this   
            ->message('product.price')                 
            ->field('uuid',$product->uuid)
            ->field('items',$product->getPriceList($currencyId)->toArray());
    }
}
