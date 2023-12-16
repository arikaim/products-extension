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
     * Update product options
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateOptions($request, $response, $data) 
    {         
        $data
            ->validate(true);

        $prices = $data->get('price',null);

        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id');
            return false;
        }
        
        // check access
        $this->requireUserOrControlPanel($product->user_id);
        
        $data->offsetUnset('uuid');
        $options = Model::ProductOptions('products');

        foreach ($data->toArray() as $key => $value) {
            $options->saveOption($product->id,$key,$value);
        }
         
        $this->setResponse(true,function() use($product) {                  
            $this
                ->message('options.update')
                ->field('uuid',$product->uuid);                  
        },'errors.options.update');                                    
    }

    /**
     * Update product price list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updatePrice($request, $response, $data) 
    {         
        $data->validate(true);

        $prices = $data->get('price',null);
        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id');
            return false;
        }
        
        // check access
        $this->requireUserOrControlPanel($product->user_id);

        $priceList = Model::ProductPriceList('products');

        foreach ($prices as $key => $value) {
            $priceList->savePrice($product->id,$value,$key,null);
        }
        
        $this->setResponse(true,function() use($product) {                  
            $this
                ->message('price.update')
                ->field('uuid',$product->uuid);                  
        },'errors.price.update');                                    
    }

    /**
     * Delete user product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteUserProduct($request, $response, $data) 
    {         
        $data->validate(true);

        $uuid = $data->get('uuid',null);
        $product = Model::Products('products')->findById($uuid); 
        if ($product === null) {
            $this->error('errors.id','Not valid product id.');
            return;
        }
        
        // only product created from logged user
        if (empty($product->user_id) == true) {
            $this->error('errors.access');
            return;
        }

        $this->requireUser($product->user_id);

        $result = $product->deleteProduct();
        if ($result === false) {
            $this->error('errors.delete','Error delete product');
            return;
        }

        $this->get('event')->dispatch('product.delete',$product->toArray());        

        $this
            ->message('delete')
            ->field('uuid',$product->uuid);                                                          
    }

    /**
     * Update product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function update($request, $response, $data) 
    {         
        $data->validate(true);
        
        $price = $data->get('price',null);

        if (isset($data['product_type']) == true) {
            $data['type_id'] = $data['product_type'];
        }
        
        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id.');
            return;
        }
        
        $this->requireUser($product->user_id);

        $result = $product->update([                
            'title'       => $data['title'],
            'description' => $data['description']
        ]);               

        if ($result === false) {
            $this->error('errors.update','Error save product.');
            return;
        }

        if (empty($price) == false) {
            // save price
            Model::ProductPriceList('products')->savePrice($product->id,'price',$price,"USD"); 
        }

        // dispatch event
        $this->get('event')->dispatch('product.update',$product->toArray()); 

        $this
            ->message('update')
            ->field('uuid',$product->uuid);                                                          
    }

    /**
     * Add product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function add($request, $response, $data) 
    {         
        $data
            ->addRule('text:min=2|required','title')
            ->addRule('number:required','product_type')            
            ->validate(true);

        $description = $data->get('description',null);
        $price = $data->get('price',null);
        $userId = $this->getUserId();
      
        $model = Model::Products('products');
        if ($model->hasProduct($data['title'],$userId) == true) {
            $this->error('errors.exist','Product with this name exists.');
            return;
        }
 
        $product = $model->create([
            'type_id'     => $data['product_type'],
            'title'       => $data['title'],
            'user_id'     => $userId,
            'description' => $description
        ]);

        if ($product == null) {
            $this->error('errors.add','Error add product');
            return;
        }

        if (empty($price) == false) {
            // save price
            Model::ProductPriceList('products')->savePrice($product->id,$price); 
        }

        // dispatch event
        $this->get('event')->dispatch('product.add',$product->toArray());    

        $this
            ->message('add')
            ->field('uuid',$product->uuid);                                             
    }

    /**
     * Get products list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getList($request, $response, $data) 
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
        $data->validate(true);

        $dataField = $data->get('data_field','uuid');
        $search = $data->get('query','');
        $size = $data->get('size',15);
        $userId = $data->get('user',$this->getUserId());

        $products = Model::Products('products')
            ->userQuery($userId)
            ->where('title','like','%' . $search . '%')
            ->take($size)
            ->get();

        if ($products == null) {
            $this->error('errors.list');
            return;
        }
           
        $items = [];
        foreach ($products as $item) {
            $items[] = [
                'name'  => $item['title'],
                'value' => $item[$dataField]
            ];
        }

        $this                    
            ->field('success',true)
            ->field('results',$items);  
                                        
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
    public function productDetails($request, $response, $data) 
    {          
        $data
            ->addRule('text:required','uuid')
            ->validate(true);    

        $product = Model::Products('products')->findById($data['uuid']);
        if ($product == null) {
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
    }
}
