<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ApiController;
use Arikaim\Extensions\Products\Controllers\Traits\Products;

/**
 * Products api controller
*/
class ProductsApi extends ApiController
{
    use Products;

    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('current>products.messages');
    }

    /**
     * Delete user product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteUserProductController($request, $response, $data) 
    {         
        $data->validate(true);
        $uuid = $data->get('uuid',null);
        $product = Model::Products('products')->findById($uuid); 
        if (\is_object($product) == false) {
            $this->error('errors.id');
            return;
        }
        
        // only product created from logged user
        if (empty($product->user_id) == true) {
            $this->error('errors.access');
            return;
        }
        $this->requireUser($product->user_id);

        $result = $product->deleteProduct();

        $this->setResponse(($result !== false),function() use($product) { 
            $this->get('event')->dispatch('product.delete',$product->toArray());                  
            $this
                ->message('delete')
                ->field('uuid',$product->uuid);                  
        },'errors.delete');                                    
    }

    /**
     * Update product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {         
        $data->validate(true);
        
        $price = $data->get('price',null);

        if (isset($data['product_type']) == true) {
            $data['type_id'] = $data['product_type'];
        }
        
        $product = Model::Products('products')->findById($data['uuid']); 
        if (\is_object($product) == false) {
            $this->error('errors.update');
            return;
        }
        
        $this->requireUser($product->user_id);

        $result = $product->update([                
            'title'       => $data['title'],
            'description' => $data['description']
        ]);               

        if (empty($price) != true) {
            // save price
            Model::ProductPriceList('products')->savePrice($product->id,'price',$price,"USD"); 
        }

        $this->setResponse(($result !== false),function() use($product) { 
            $this->get('event')->dispatch('product.update',$product->toArray());                  
            $this
                ->message('update')
                ->field('uuid',$product->uuid);                  
        },'errors.update');                                    
    }

    /**
     * Add product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addController($request, $response, $data) 
    {         
        $data
            ->addRule('text:min=2|required','title')
            ->addRule('number|required','product_type')            
            ->validate(true);

        $description = $data->get('description',null);
        $price = $data->get('price',null);
        $userId = $this->getUserId();
      
        $model = Model::Products('products');
        if ($model->hasProduct($data['title'],$userId) == true) {
            $this->error('errors.exist');
            return;
        }
 
        $product = $model->create([
            'type_id'     => $data['product_type'],
            'title'       => $data['title'],
            'user_id'     => $userId,
            'description' => $description
        ]);

        if (\is_object($product) == false) {
            $this->error('errors.add');
            return;
        }

        if (empty($price) != true) {
            // save price
            Model::ProductPriceList('products')->savePrice($product->id,'price',$price,"USD"); 
        }
       
        $this->setResponse(\is_object($product),function() use($product) {     
            $this->get('event')->dispatch('product.add',$product->toArray());             
            $this
                ->message('add')
                ->field('uuid',$product->uuid);                  
        },'errors.add');                                    
    }

    /**
     * Get products list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getListController($request, $response, $data) 
    {          
        $products = $this->getProductsList($request,$response,$data);
        $result = $products->toArray();
             
        $this->field('paginator',$result['paginator']); 
        $this->field('items',$result['rows']);
    }
    
    /**
     * Get products list (for products dropdown)
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getDropdownList($request, $response, $data) 
    {       
        $this->onDataValid(function($data) {   
            $dataField = $data->get('data_field','uuid');
            $search = $data->get('query','');
            $size = $data->get('size',15);

            $query = Model::Products('products');
            $model = $query->where('title','like','%' . $search . '%')->take($size)->get();

            $this->setResponse(\is_object($model),function() use($model,$dataField) {     
                $items = [];
                foreach ($model as $item) {
                    $items[]= [
                        'name'  => $item['title'],
                        'value' => $item[$dataField]
                    ];
                }
                $this                    
                    ->field('success',true)
                    ->field('results',$items);  
            },'errors.list');                                
        });
        $data->validate();

        return $this->getResponse(true);
    }

    /**
     * Get product details
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getProductDetailsController($request, $response, $data) 
    {          
        $this->onDataValid(function($data) { 
            $product = Model::Products('products')->findById($data['uuid']);
            if (empty($product) == true) {
                $this->error('Product not exist.');
                return false;
            }
            if ($product->status != $product->ACTIVE()) {
                $this->error('Product not exist or not published.');
                return false;
            }

            $this->setResultFields($product->toArray());

            $categories = [];
            foreach ($product->categories as $item) {
                $categories[] = [
                    'title' => $item->getTitle(),
                    'slug'  => $item->getSlug()
                ];
            }
        
            $this->setResultFields($categories,'categories');
        });
        $data
            ->addRule('text:required','uuid')
            ->validate();      
    }
}
